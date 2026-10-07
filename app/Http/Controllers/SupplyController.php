<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductSupply;
use Illuminate\Support\Facades\DB;

class SupplyController extends Controller
{

    /**
     * Display all supplies
     */
    public function index()
    {
        $supplies = ProductSupply::with(['product.images', 'supplier'])
            ->latest()
            ->get();

        $products = Product::where('status', 'available')->get();
        $suppliers = Supplier::all();

        return view('pages.supply.productSupply', compact('supplies', 'products', 'suppliers'));
    }

    /**
     * Store new supply
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'qty_supplied' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'supply_date' => 'nullable|date',
            'status' => 'nullable|in:approved,pending,cancelled'
        ]);

        try {
            DB::beginTransaction();

            // Generate unique invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            while (ProductSupply::where('invoice_number', $invoiceNumber)->exists()) {
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            // Calculate total amount
            $totalAmount = $validated['unit_price'] * $validated['qty_supplied'];

            // Create supply record
            $supply = ProductSupply::create([
                'invoice_number' => $invoiceNumber,
                'supply_date' => $validated['supply_date'] ?? now(),
                'product_id' => $validated['product_id'],
                'supplier_id' => $validated['supplier_id'],
                'unit_price' => $validated['unit_price'],
                'qty_supplied' => $validated['qty_supplied'],
                'total_amount' => $totalAmount,
                'status' => $validated['status'] ?? 'pending'
            ]);

            // Only increment product quantity if status is "approved"
            if ($supply->status === 'approved') {
                Product::where('id', $validated['product_id'])
                    ->increment('product_qty', $validated['qty_supplied']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $supply->status === 'approved'
                    ? 'Supply created and approved! Product quantity updated.'
                    : 'Supply created successfully! Waiting for approval.',
                'supply' => $supply,
                'invoice_number' => $invoiceNumber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create supply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show supply details
     */
    public function show($id)
    {
        $supply = ProductSupply::with(['product', 'supplier'])
            ->findOrFail($id);

            return response()->json($supply);
    }

    /**
     * Approve supply
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $supply = ProductSupply::findOrFail($id);

            // Only approve if currently pending
            if ($supply->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supply is not in pending status!'
                ], 400);
            }

            // Update status to approved
            $supply->update(['status' => 'approved']);

            // Now increment the product quantity
            Product::where('id', $supply->product_id)
                ->increment('product_qty', $supply->qty_supplied);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Supply approved successfully! Product quantity updated.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve supply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel supply
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            $supply = ProductSupply::findOrFail($id);

            // If already cancelled, don't do anything
            if ($supply->status === 'cancelled') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supply is already cancelled!'
                ], 400);
            }

            // If approved, we need to decrease product qty
            if ($supply->status === 'approved') {
                Product::where('id', $supply->product_id)
                    ->decrement('product_qty', $supply->qty_supplied);
            }

            // Update status to cancelled
            $supply->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Supply cancelled successfully!' .
                    ($supply->status === 'approved' ? ' Product quantity restored.' : '')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cancel supply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supplies by supplier
     */
    public function getBySupplier($supplierId)
    {
        $supplies = ProductSupply::with(['product'])
            ->where('supplier_id', $supplierId)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'supplies' => $supplies
        ]);
    }

    /**
     * Get supplies by product
     */
    public function getByProduct($productId)
    {
        $supplies = ProductSupply::with(['supplier'])
            ->where('product_id', $productId)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'supplies' => $supplies
        ]);
    }

    /**
     * Display supply analysis page
     */
    public function analysis()
    {
        $products = Product::with(['productSupplies.supplier', 'saleItems.sale', 'images'])
            ->get();

        // Calculate summary metrics
        $totalProducts = $products->count();
        $inStockCount = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;
        $reorderSoonCount = 0;
        $totalDailySales = 0;

        foreach ($products as $product) {
            // Calculate average daily sales
            $salesLast30Days = $product->saleItems()
                ->whereHas('sale', function ($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                })
                ->sum('qty');
            $avgDailySales = $salesLast30Days / 30;
            $totalDailySales += $avgDailySales;

            // Calculate days left
            $daysLeft = $avgDailySales > 0 ? ceil($product->product_qty / $avgDailySales) : 999;

            // Categorize stock status
            if ($product->product_qty == 0) {
                $outOfStockCount++;
            } elseif ($product->product_qty <= $product->stock_alert) {
                $lowStockCount++;
            } elseif ($daysLeft <= 7) {
                $reorderSoonCount++;
            } else {
                $inStockCount++;
            }
        }

        $avgDailySales = $totalProducts > 0 ? $totalDailySales / $totalProducts : 0;

        // Prepare chart data
        $chartData = [
            'inStock' => $inStockCount,
            'lowStock' => $lowStockCount,
            'outOfStock' => $outOfStockCount,
            'topSuppliers' => $this->getTopSuppliers(),
            'supplyTrend' => $this->getSupplyTrend(),
            'stockVelocity' => $this->getStockVelocity()
        ];

        return view("pages.supply.analysis", compact(
            'products',
            'totalProducts',
            'inStockCount',
            'lowStockCount',
            'outOfStockCount',
            'avgDailySales',
            'reorderSoonCount',
            'chartData'
        ));
    }

    /**
     * Get top suppliers by supply volume
     */
    private function getTopSuppliers()
    {
        return Supplier::withSum('productSupplies', 'qty_supplied')
            ->orderBy('product_supplies_sum_qty_supplied', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($supplier) {
                return [
                    'name' => $supplier->name,
                    'total' => $supplier->product_supplies_sum_qty_supplied ?? 0
                ];
            })
            ->toArray();
    }

    /**
     * Get supply vs sales trend (last 30 days)
     */
    private function getSupplyTrend()
    {
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
        }

        $trend = [];
        foreach ($dates as $date) {
            $supplied = ProductSupply::whereDate('created_at', $date)
                ->where('status', 'approved')
                ->sum('qty_supplied');

            $sold = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->whereDate('sales.created_at', $date)
                ->sum('sale_items.qty');

            $trend[] = [
                'date' => \Carbon\Carbon::parse($date)->format('M d'),
                'supplied' => $supplied,
                'sold' => $sold
            ];
        }

        return $trend;
    }

    /**
     * Get stock velocity (days to deplete) for products
     */
    private function getStockVelocity()
    {
        $products = Product::with('saleItems')
            ->where('product_qty', '>', 0)
            ->limit(15)
            ->get();

        $velocity = [];
        foreach ($products as $product) {
            $salesLast30Days = $product->saleItems()
                ->whereHas('sale', function ($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                })
                ->sum('qty');

            $avgDailySales = $salesLast30Days / 30;
            $daysLeft = $avgDailySales > 0 ? ceil($product->product_qty / $avgDailySales) : 999;

            $velocity[] = [
                'name' => substr($product->name, 0, 15),
                'days' => min($daysLeft, 999)
            ];
        }

        return $velocity;
    }

    /**
     * Get detailed supply analysis for a single product
     */
    public function getProductSupplyAnalysis($id)
    {
        $product = Product::with(['productSupplies.supplier', 'saleItems.sale', 'images'])
            ->findOrFail($id);

        // Current stock
        $currentStock = $product->product_qty;

        // Total supplied
        $totalSupplied = $product->productSupplies()
            ->where('status', 'approved')
            ->sum('qty_supplied');

        // Total sold
        $totalSold = $product->saleItems->sum('qty');

        // Average daily sales
        $salesLast30Days = $product->saleItems()
            ->whereHas('sale', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })
            ->sum('qty');
        $avgDailySales = number_format($salesLast30Days / 30, 1);

        // Supply history
        $supplyHistory = $product->productSupplies()
            ->with('supplier')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($supply) {
                return [
                    'date' => $supply->supply_date->format('d M Y'),
                    'supplier' => $supply->supplier->name ?? 'N/A',
                    'qty' => $supply->qty_supplied,
                    'unitPrice' => number_format($supply->unit_price, 2),
                    'total' => number_format($supply->total_amount, 2),
                    'status' => $supply->status
                ];
            });

        // Trend data (last 30 days stock levels)
        $trendData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);

            // Calculate stock at that point (current stock + sales after that date - supplies after that date)
            $salesAfter = $product->saleItems()
                ->whereHas('sale', function ($q) use ($date) {
                    $q->where('created_at', '>', $date);
                })
                ->sum('qty');

            $suppliesAfter = $product->productSupplies()
                ->where('status', 'approved')
                ->where('supply_date', '>', $date)
                ->sum('qty_supplied');

            $stockAtDate = $currentStock + $salesAfter - $suppliesAfter;

            $trendData[] = [
                'date' => $date->format('M d'),
                'stock' => max(0, $stockAtDate)
            ];
        }

        return response()->json([
            'status' => 'success',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'image' => $product->images->first()->url ?? null
            ],
            'currentStock' => $currentStock,
            'totalSupplied' => $totalSupplied,
            'totalSold' => $totalSold,
            'avgDailySales' => $avgDailySales,
            'supplyHistory' => $supplyHistory,
            'trendData' => $trendData
        ]);
    }

    /**
     * Export supply analysis to Excel
     */
    public function exportAnalysis()
    {
        $products = Product::with(['productSupplies', 'saleItems'])->get();

        $data = [];

        foreach ($products as $product) {
            $lastSupply = $product->productSupplies()->latest()->first();

            $salesLast30Days = $product->saleItems()
                ->whereHas('sale', function ($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                })
                ->sum('qty');
            $avgDailySales = $salesLast30Days / 30;
            $daysLeft = $avgDailySales > 0 ? ceil($product->product_qty / $avgDailySales) : 999;

            // Determine status
            if ($product->product_qty == 0) {
                $status = 'Out of Stock';
            } elseif ($product->product_qty <= $product->stock_alert) {
                $status = 'Low Stock';
            } elseif ($daysLeft <= 7) {
                $status = 'Reorder Soon';
            } else {
                $status = 'In Stock';
            }

            $data[] = [
                'Product Name' => $product->name,
                'Product Code' => $product->code,
                'Current Stock' => $product->product_qty,
                'Stock Alert' => $product->stock_alert,
                'Last Supply Qty' => $lastSupply->qty_supplied ?? 0,
                'Last Supply Date' => $lastSupply ? $lastSupply->supply_date->format('Y-m-d') : 'N/A',
                'Last Supplier' => $lastSupply && $lastSupply->supplier ? $lastSupply->supplier->name : 'N/A',
                'Avg Daily Sales' => round($avgDailySales, 2),
                'Days Left' => $daysLeft < 999 ? $daysLeft : 'Unlimited',
                'Status' => $status
            ];
        }

        // Create CSV
        $filename = 'supply-analysis-' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Add headers
        fputcsv($output, array_keys($data[0]));

        // Add data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts()
    {
        $products = Product::where('product_qty', '>', 0)
            ->whereColumn('product_qty', '<=', 'stock_alert')
            ->with(['productSupplies.supplier', 'images'])
            ->get();

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

    /**
     * Get products needing reorder
     */
    public function getReorderProducts()
    {
        $products = Product::with(['saleItems', 'images'])
            ->where('product_qty', '>', 0)
            ->get()
            ->filter(function ($product) {
                $salesLast30Days = $product->saleItems()
                    ->whereHas('sale', function ($q) {
                        $q->where('created_at', '>=', now()->subDays(30));
                    })
                    ->sum('qty');
                $avgDailySales = $salesLast30Days / 30;
                $daysLeft = $avgDailySales > 0 ? ceil($product->product_qty / $avgDailySales) : 999;

                return $daysLeft <= 7;
            });

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

}
