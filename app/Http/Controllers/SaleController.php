<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{

    /**
     * Display POS interface
     */
    // public function index()
    // {
    //     $products = Product::with(['brand', 'category'])
    //         ->where('status', 'available')
    //         ->where('product_qty', '>', 0)
    //         ->get();

    //     return view('pages.sale.pos', compact('products'));
    // }

    //     /**
    //  * Get all sales
    //  */
    // public function AllSales()
    // {

    //     $sales = Sale::with(['cashier', 'saleItems.product'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('pages.sale.sales', compact('sales'));
    // }

    /**
     * Display POS interface
     */
    public function index()
    {
        $products = Product::with(['brand', 'category'])
            ->where('status', 'available')
            ->where('product_qty', '>', 0)
            ->get();

        return view('pages.sale.pos', compact('products'));
    }

    /**
     * Get all sales
     */
    public function AllSales()
    {
        $user = auth()->user();
        if ($user->hasRole(['admin','super-admin'])) {
            $sales = Sale::with(['cashier', 'saleItems.product'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('pages.sale.sales', compact('sales'));
        }

        if ($user->hasRole('cashier')) {
            $sales = Sale::with([
                'cashier',
                'saleItems.product'
                ])
                ->where('cashier_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('pages.sale.sales', compact('sales'));
        }
    }

    /**
     * Get single sale details
     */
    public function SaleDetails($id)
    {
        $sale = Sale::with(['cashier', 'saleItems.product'])
            ->findOrFail($id);

        abort_unless(auth()->user()->hasRole(['admin', 'super-admin']) || $sale->cashier_id === auth()->id(), 403);

        // Check if it's an AJAX request
        // if (request()->expectsJson() || request()->ajax()) {
            return response()->json($sale);
        // }

        // return view('admin.sales.details', compact('sale'));
    }

    /**
     * Create new sale (Main POS Logic)
     */
    public function CreateSale(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|distinct|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'shipping' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:pos,wire,cash',
            'payment_status' => 'required|in:paid,pending'
        ]);

        try {
            DB::beginTransaction();

            // 1. Check stock availability for all items
            $items = collect($validated['items'])->sortBy('product_id')->values()->all();
            $validated['items'] = $items;
            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->status !== 'available') {
                    throw ValidationException::withMessages(['items' => "Product is not available: {$product->name}"]);
                }

                if ($product->product_qty < $item['qty']) {
                    throw ValidationException::withMessages(['items' => "Insufficient stock for product: {$product->name}. Available: {$product->product_qty}, Requested: {$item['qty']}"]);
                }
            }

            // 2. Calculate subtotal
            $subTotal = 0;
            foreach ($validated['items'] as &$item) {
                $item['unit_price'] = Product::findOrFail($item['product_id'])->price;
                $subTotal += $item['unit_price'] * $item['qty'];
            }
            unset($item);

            if ($validated['discount_type'] === 'percentage' && ($validated['discount'] ?? 0) > 100) {
                throw ValidationException::withMessages(['discount' => 'Percentage discount cannot exceed 100.']);
            }

            // 3. Calculate discount amount
            $discountAmount = 0;
            if (isset($validated['discount']) && $validated['discount'] > 0) {
                if ($validated['discount_type'] === 'percentage') {
                    $discountAmount = ($subTotal * $validated['discount']) / 100;
                } else {
                    $discountAmount = $validated['discount'];
                }
            }

            // 4. Calculate grand total
            $shipping = $validated['shipping'] ?? 0;
            $grandTotal = $subTotal - $discountAmount + $shipping;

            if ($grandTotal < 0) {
                throw ValidationException::withMessages(['discount' => 'Discount cannot exceed the sale total.']);
            }

            // 5. Generate unique receipt number
            $receiptNumber = 'RCP-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            while (Sale::where('receipt_number', $receiptNumber)->exists()) {
                $receiptNumber = 'RCP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            // 6. Create sale record
            $sale = Sale::create([
                'sub_total' => $subTotal,
                'discount' => $validated['discount'] ?? 0,
                'discount_type' => $validated['discount_type'],
                'shipping' => $shipping,
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'cashier_id' => Auth::id(),
                'payment_status' => $validated['payment_status'],
                'receipt_number' => $receiptNumber
            ]);

            // 7. Create sale items and reduce product quantities
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['unit_price'] * $item['qty'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice
                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('product_qty', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale completed successfully!',
                'sale' => $sale,
                'receipt_number' => $receiptNumber,
                'grand_total' => $grandTotal
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Sale failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate receipt (for printing)
     */
    public function GenerateReceipt($receiptNumber)
    {
        $sale = Sale::with(['cashier', 'saleItems.product'])
            ->where('receipt_number', $receiptNumber)
            ->firstOrFail();

        abort_unless(auth()->user()->hasRole(['admin', 'super-admin']) || $sale->cashier_id === auth()->id(), 403);

        return view('pages.sale.receipt', compact('sale'));
    }

    /**
     * Get receipt data as JSON (for AJAX)
     */
    public function GetReceiptData($receiptNumber)
    {
        $sale = Sale::with(['cashier', 'saleItems.product.brand'])
            ->where('receipt_number', $receiptNumber)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'sale' => $sale
        ]);
    }

    /**
     * Process refund
     */
    public function RefundSale(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|integer|exists:sales,id',
            'reason' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::with('saleItems')->findOrFail($validated['sale_id']);

            if ($sale->payment_status === 'refunded') {
                throw new \Exception("This sale has already been refunded!");
            }

            // Restore product quantities
            foreach ($sale->saleItems as $item) {
                Product::where('id', $item->product_id)
                    ->increment('product_qty', $item->qty);
            }

            $sale->update(['payment_status' => 'refunded']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale refunded successfully! Product quantities restored.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Refund failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products for POS (AJAX endpoint)
     */
    public function SearchProducts(Request $request)
    {
        $search = $request->input('search');

        $products = Product::with(['brand', 'category'])
            ->where('status', 'available')
            ->where('product_qty', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

            // dd($search);

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

    /**
     * Get product details for POS
     */
    public function GetProduct($id)
    {
        $product = Product::with(['brand', 'category'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }

    /**
     * Daily sales report
     */
    public function DailySalesReport()
    {
        $today = now()->toDateString();

        $sales = Sale::with(['saleItems'])
            ->whereDate('created_at', $today)
            ->get();

        $totalSales = $sales->sum('grand_total');
        $totalDiscount = $sales->sum('discount');
        $totalShipping = $sales->sum('shipping');
        $transactionCount = $sales->count();

        return view('admin.reports.daily', compact('sales', 'totalSales', 'totalDiscount', 'totalShipping', 'transactionCount'));
    }

}
