<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->latest()
            ->get();

        $categories = ProductCategory::all();

        return view('pages.product.products', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $brands = Brand::all();

        return view('pages.product.add', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code',
            'category_id' => 'integer|exists:product_categories,id',
            'brand_id' => 'integer|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock_alert' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:available,unavailable,on_hold',
            'note' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif'
        ]);

        try {
            DB::beginTransaction();

            // Create product (quantity starts at 0)
            $product = Product::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'price' => $validated['price'],
                'stock_alert' => $validated['stock_alert'],
                'discount' => $validated['discount'] ?? 0,
                'status' => $validated['status'],
                'note' => $validated['note'],
                'product_qty' => 0 // Always start at 0
            ]);

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $manager = new ImageManager(new Driver());
                    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                    $img = $manager->read($image);
                    $img->scale(400, 400); // Resize to 400x400
                    $img->save(public_path("uploads/products/$name_gen"));

                    // Save image record
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => "uploads/products/$name_gen"
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products')
                ->with('success', 'Product created successfully! Add stock via Product Supplies.');

        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());


            return back()->with('error', 'Failed to create product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = ProductCategory::all();
        $brands = Brand::all();

        return view('pages.product.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $id,
            'category_id' => 'required|integer|exists:product_categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock_alert' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:available,unavailable,on_hold',
            'note' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'nullable|integer|exists:product_images,id'
        ]);

        try {
            DB::beginTransaction();

            // Update product (DO NOT update product_qty - only supplies/sales change it)
            $product->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'price' => $validated['price'],
                'stock_alert' => $validated['stock_alert'],
                'discount' => $validated['discount'] ?? null,
                'status' => $validated['status'],
                'note' => $validated['note']
                // product_qty is intentionally NOT updated here
            ]);

            // Remove selected images
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        // Delete file from storage
                        if (file_exists(public_path($image->url))) {
                            unlink(public_path($image->url));
                        }
                        $image->delete();
                    }
                }
            }

            // Add new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $manager = new ImageManager(new Driver());
                    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                    $img = $manager->read($image);
                    $img->scale(400, 400);
                    $img->save(public_path("uploads/products/$name_gen"));

                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => "uploads/products/$name_gen"
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteImage(Request $request)
    {
        $validated = $request->validate([
            'image_id' => 'required|integer|exists:product_images,id'
        ]);

        try {
            $image = ProductImage::findOrFail($validated['image_id']);

            // Delete file from storage
            if (file_exists(public_path($image->url))) {
                unlink(public_path($image->url));
            }

            $image->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Image deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
            'status' => 'required|in:available,unavailable,on_hold'
        ]);

        try {
            Product::whereIn('id', $validated['product_ids'])
                ->update(['status' => $validated['status']]);

            return response()->json([
                'status' => 'success',
                'message' => 'Products status updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLowStock()
    {
        $products = Product::with(['category', 'brand'])
            ->whereColumn('product_qty', '<=', 'stock_alert')
            ->where('product_qty', '>', 0)
            ->get();

        // return view('admin.products.low-stock', compact('products'));
    }

    public function getOutOfStock()
    {
        $products = Product::with(['category', 'brand'])
            ->where('product_qty', 0)
            ->get();

        // return view('admin.products.out-of-stock', compact('products'));
    }

    public function details($id)
    {
        $product = Product::with([
            'images:id,product_id,url',
            'category:id,name',
            'brand:id,name',
        ])->findOrFail($id);

        return response()->json($product);
    }

    /**
     * Display product analysis page
     */
    public function analysis()
    {

        // dd('het');
        $products = Product::with(['productSupplies', 'saleItems.sale', 'images'])
            ->get();

        // Calculate metrics
        $profitableCount = 0;
        $lossCount = 0;
        $breakEvenCount = 0;
        $totalMargin = 0;
        $productCount = 0;

        $chartData = [
            'topProducts' => [],
            'allProducts' => [],
            'profitableCount' => 0,
            'lossCount' => 0,
            'breakEvenCount' => 0
        ];

        $analysisData = [];

        foreach ($products as $product) {
            // Calculate average cost price from supplies
            $avgCostPrice = $product->productSupplies->avg('unit_price') ?? 0;
            $sellingPrice = $product->price;

            // Calculate profit/loss per unit
            $profitLoss = $sellingPrice - $avgCostPrice;

            // Calculate margin percentage
            $marginPercentage = $avgCostPrice > 0 ? (($profitLoss / $avgCostPrice) * 100) : 0;

            // Count by status
            if ($profitLoss > 0) {
                $profitableCount++;
                $chartData['profitableCount']++;
            } elseif ($profitLoss < 0) {
                $lossCount++;
                $chartData['lossCount']++;
            } else {
                $breakEvenCount++;
                $chartData['breakEvenCount']++;
            }

            if ($avgCostPrice > 0) {
                $totalMargin += $marginPercentage;
                $productCount++;
            }

            // Store for charts
            $analysisData[] = [
                'name' => substr($product->name, 0, 20),
                'margin' => round($marginPercentage, 2),
                'costPrice' => round($avgCostPrice, 2),
                'sellingPrice' => round($sellingPrice, 2),
                'profit' => round($profitLoss, 2)
            ];
        }

        // Sort by margin for top products
        usort($analysisData, function ($a, $b) {
            return $b['margin'] <=> $a['margin'];
        });

        $chartData['topProducts'] = array_slice($analysisData, 0, 10);
        $chartData['allProducts'] = array_slice($analysisData, 0, 20); // Limit for performance

        $totalProfitMargin = $productCount > 0 ? ($totalMargin / $productCount) : 0;

        return view('pages.product.analysis', compact(
            'products',
            'profitableCount',
            'lossCount',
            'breakEvenCount',
            'totalProfitMargin',
            'chartData'
        ));
    }

    /**
     * Get detailed analysis for a single product
     */
    public function getProductAnalysis($id)
    {
        $product = Product::with(['productSupplies', 'saleItems.sale', 'images'])
            ->findOrFail($id);

        // Calculate average cost price from supplies
        $avgCostPrice = $product->productSupplies->avg('unit_price') ?? 0;
        $sellingPrice = $product->price;

        // Calculate profit/loss per unit
        $profitLoss = $sellingPrice - $avgCostPrice;

        // Calculate margin percentage
        $marginPercentage = $avgCostPrice > 0 ? (($profitLoss / $avgCostPrice) * 100) : 0;

        // Get total sold from sales
        $totalSold = $product->saleItems->sum('qty') ?? 0;

        // Calculate total revenue
        $totalRevenue = $product->saleItems->sum('total_price') ?? 0;

        // Calculate total profit
        $totalProfit = $totalSold * $profitLoss;

        // Get sales trend (last 30 days)
        $salesTrend = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sale_items.product_id', $id)
            ->where('sales.created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(sales.created_at) as date'),
                DB::raw('SUM(sale_items.qty) as qty')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => \Carbon\Carbon::parse($item->date)->format('M d'),
                    'qty' => $item->qty
                ];
            });

        return response()->json([
            'status' => 'success',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'image' => $product->images->first()->url ?? null
            ],
            'avgCostPrice' => $avgCostPrice,
            'sellingPrice' => $sellingPrice,
            'profitLoss' => $profitLoss,
            'marginPercentage' => $marginPercentage,
            'totalSold' => $totalSold,
            'totalRevenue' => $totalRevenue,
            'totalProfit' => $totalProfit,
            'salesTrend' => $salesTrend
        ]);
    }

    /**
     * Export analysis to Excel
     */
    public function exportAnalysis()
    {
        $products = Product::with(['productSupplies', 'saleItems'])->get();

        $data = [];

        foreach ($products as $product) {
            $avgCostPrice = $product->productSupplies->avg('unit_price') ?? 0;
            $sellingPrice = $product->price;
            $profitLoss = $sellingPrice - $avgCostPrice;
            $marginPercentage = $avgCostPrice > 0 ? (($profitLoss / $avgCostPrice) * 100) : 0;
            $totalSold = $product->saleItems->sum('qty') ?? 0;
            $totalRevenue = $product->saleItems->sum('total_price') ?? 0;
            $totalProfit = $totalSold * $profitLoss;

            $data[] = [
                'Product Name' => $product->name,
                'Product Code' => $product->code,
                'Cost Price' => $avgCostPrice,
                'Selling Price' => $sellingPrice,
                'Profit/Loss per Unit' => $profitLoss,
                'Margin %' => round($marginPercentage, 2),
                'Total Sold' => $totalSold,
                'Total Revenue' => $totalRevenue,
                'Total Profit' => $totalProfit,
                'Status' => $profitLoss > 0 ? 'Profitable' : ($profitLoss < 0 ? 'Loss' : 'Break Even')
            ];
        }

        // Create CSV
        $filename = 'product-analysis-' . date('Y-m-d') . '.csv';

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
     * Get profit/loss comparison
     */
    public function profitLossComparison()
    {
        $products = Product::with(['productSupplies', 'saleItems'])
            ->get()
            ->map(function ($product) {
                $avgCostPrice = $product->productSupplies->avg('unit_price') ?? 0;
                $sellingPrice = $product->price;
                $profitLoss = $sellingPrice - $avgCostPrice;
                $totalSold = $product->saleItems->sum('qty') ?? 0;
                $totalProfit = $totalSold * $profitLoss;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'costPrice' => $avgCostPrice,
                    'sellingPrice' => $sellingPrice,
                    'profitLoss' => $profitLoss,
                    'totalSold' => $totalSold,
                    'totalProfit' => $totalProfit
                ];
            });

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

}
