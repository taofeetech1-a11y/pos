<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(){

        $brands = Brand::all();

        return view("pages.brand.brands",compact("brands"));

    }

    public function create(){

        return view("pages.brand.add");

    }

    public function store(Request $request){

        $validated = $request->validate(['name' => 'required|string|unique:brands,name']);

        Brand::create($validated);

        return redirect()->route('brands')->with('success','');

    }

    public function edit($id){

        $brand = Brand::findOrFail($id);

        return view('pages.brand.edit',compact('brand'));

    }

    public function update(Request $request, $id){

        $validated = $request->validate(['name'=> 'required|string|unique:brands,name']);

        Brand::findOrFail($id)->update($validated);

        return redirect()->route('brands')->with('success','');

    }

    public function delete($id){

        Brand::findOrFail($id)->delete();
        return redirect()->route('brands')->with('success','');

    }



        /**
     * Display brand analysis page
     */
    public function analysis()
    {
        $brands = Brand::with(['products.saleItems', 'products.productSupplies'])
            ->get();

        // Calculate summary metrics
        $totalBrands = $brands->count();
        $totalProducts = 0;
        $totalRevenueAll = 0;
        $topBrandName = 'N/A';
        $topBrandRevenue = 0;

        foreach ($brands as $brand) {
            $totalProducts += $brand->products->count();

            $brandRevenue = 0;
            foreach ($brand->products as $product) {
                $brandRevenue += $product->saleItems->sum('total_price');
            }

            $totalRevenueAll += $brandRevenue;

            if ($brandRevenue > $topBrandRevenue) {
                $topBrandRevenue = $brandRevenue;
                $topBrandName = $brand->name;
            }
        }

        // Prepare chart data
        $chartData = [
            'marketShare' => $this->getMarketShare($brands),
            'salesVolume' => $this->getSalesVolume($brands),
            'revenueProfit' => $this->getRevenueProfit($brands),
            'performanceTrend' => $this->getPerformanceTrend($brands),
            'trendDates' => $this->getTrendDates()
        ];

        return view('pages.brand.analysis', compact(
            'brands',
            'totalBrands',
            'totalProducts',
            'totalRevenueAll',
            'topBrandName',
            'chartData',
            'totalRevenueAll'
        ));
    }

    /**
     * Get market share data
     */
    private function getMarketShare($brands)
    {
        return $brands->map(function ($brand) {
            $revenue = 0;
            foreach ($brand->products as $product) {
                $revenue += $product->saleItems->sum('total_price');
            }

            return [
                'name' => $brand->name,
                'revenue' => $revenue
            ];
        })
            ->sortByDesc('revenue')
            ->take(10)
            ->values()
            ->toArray();
    }

    /**
     * Get sales volume data
     */
    private function getSalesVolume($brands)
    {
        return $brands->map(function ($brand) {
            $sales = 0;
            foreach ($brand->products as $product) {
                $sales += $product->saleItems->sum('qty');
            }

            return [
                'name' => $brand->name,
                'sales' => $sales
            ];
        })
            ->sortByDesc('sales')
            ->take(10)
            ->values()
            ->toArray();
    }

    /**
     * Get revenue vs profit data
     */
    private function getRevenueProfit($brands)
    {
        return $brands->map(function ($brand) {
            $revenue = 0;
            $profit = 0;

            foreach ($brand->products as $product) {
                $productRevenue = $product->saleItems->sum('total_price');
                $revenue += $productRevenue;

                $costPrice = $product->productSupplies->avg('unit_price') ?? 0;
                $salesQty = $product->saleItems->sum('qty');
                $productProfit = ($product->price - $costPrice) * $salesQty;
                $profit += $productProfit;
            }

            return [
                'name' => $brand->name,
                'revenue' => $revenue,
                'profit' => $profit
            ];
        })
            ->sortByDesc('revenue')
            ->take(10)
            ->values()
            ->toArray();
    }

    /**
     * Get performance trend (last 30 days)
     */
    private function getPerformanceTrend($brands)
    {
        $topBrands = $brands->sortByDesc(function ($brand) {
            $revenue = 0;
            foreach ($brand->products as $product) {
                $revenue += $product->saleItems->sum('total_price');
            }
            return $revenue;
        })->take(5);

        $trend = [];
        foreach ($topBrands as $brand) {
            $brandData = [];

            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');

                $daySales = 0;
                foreach ($brand->products as $product) {
                    $sales = $product->saleItems()
                        ->whereHas('sale', function ($q) use ($date) {
                            $q->whereDate('created_at', $date);
                        })
                        ->sum('qty');
                    $daySales += $sales;
                }

                $brandData[] = $daySales;
            }

            $trend[] = [
                'name' => $brand->name,
                'data' => $brandData
            ];
        }

        return $trend;
    }

    /**
     * Get trend dates
     */
    private function getTrendDates()
    {
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('M d');
        }
        return $dates;
    }

    /**
     * Get detailed analysis for a single brand
     */
    public function getBrandAnalysis($id)
    {
        $brand = Brand::with(['products.saleItems', 'products.productSupplies'])
            ->findOrFail($id);

        $productsCount = $brand->products->count();
        $totalSales = 0;
        $totalRevenue = 0;
        $totalProfit = 0;

        $products = [];
        foreach ($brand->products as $product) {
            $productSales = $product->saleItems->sum('qty');
            $productRevenue = $product->saleItems->sum('total_price');
            $costPrice = $product->productSupplies->avg('unit_price') ?? 0;
            $productProfit = ($product->price - $costPrice) * $productSales;
            $profitMargin = $productRevenue > 0 ? (($productProfit / $productRevenue) * 100) : 0;

            $totalSales += $productSales;
            $totalRevenue += $productRevenue;
            $totalProfit += $productProfit;

            $products[] = [
                'name' => $product->name,
                'sales' => $productSales,
                'revenue' => number_format($productRevenue, 2),
                'profit' => number_format($productProfit, 2),
                'profitMargin' => number_format($profitMargin, 1)
            ];
        }

        $profitMargin = $totalRevenue > 0 ? number_format(($totalProfit / $totalRevenue) * 100, 1) : 0;

        // Calculate market share
        $allBrandsRevenue = Brand::with('products.saleItems')->get()->sum(function ($b) {
            return $b->products->sum(function ($p) {
                return $p->saleItems->sum('total_price');
            });
        });
        $marketShare = $allBrandsRevenue > 0 ? number_format(($totalRevenue / $allBrandsRevenue) * 100, 1) : 0;

        // Trend data (last 30 days)
        $trendData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $daySales = 0;
            foreach ($brand->products as $product) {
                $sales = $product->saleItems()
                    ->whereHas('sale', function ($q) use ($date) {
                        $q->whereDate('created_at', $date->format('Y-m-d'));
                    })
                    ->sum('qty');
                $daySales += $sales;
            }

            $trendData[] = [
                'date' => $date->format('M d'),
                'sales' => $daySales
            ];
        }

        return response()->json([
            'status' => 'success',
            'brand' => [
                'name' => $brand->name,
                'note' => $brand->note
            ],
            'productsCount' => $productsCount,
            'totalSales' => number_format($totalSales),
            'totalRevenue' => number_format($totalRevenue, 2),
            'totalProfit' => number_format($totalProfit, 2),
            'profitMargin' => $profitMargin,
            'marketShare' => $marketShare,
            'products' => $products,
            'trendData' => $trendData
        ]);
    }

    /**
     * Export brand analysis to Excel
     */
    public function exportAnalysis()
    {
        $brands = Brand::with(['products.saleItems', 'products.productSupplies'])->get();

        $data = [];

        foreach ($brands as $brand) {
            $productsCount = $brand->products->count();
            $totalSales = 0;
            $totalRevenue = 0;
            $totalProfit = 0;

            foreach ($brand->products as $product) {
                $productSales = $product->saleItems->sum('qty');
                $productRevenue = $product->saleItems->sum('total_price');
                $costPrice = $product->productSupplies->avg('unit_price') ?? 0;
                $productProfit = ($product->price - $costPrice) * $productSales;

                $totalSales += $productSales;
                $totalRevenue += $productRevenue;
                $totalProfit += $productProfit;
            }

            $profitMargin = $totalRevenue > 0 ? (($totalProfit / $totalRevenue) * 100) : 0;

            $data[] = [
                'Brand Name' => $brand->name,
                'Products Count' => $productsCount,
                'Total Sales (Units)' => $totalSales,
                'Total Revenue' => $totalRevenue,
                'Total Profit' => $totalProfit,
                'Profit Margin %' => round($profitMargin, 2),
                'Performance' => $profitMargin >= 30 ? 'Excellent' : ($profitMargin >= 20 ? 'Good' : ($profitMargin >= 10 ? 'Average' : 'Poor'))
            ];
        }

        // Create CSV
        $filename = 'brand-analysis-' . date('Y-m-d') . '.csv';

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
     * Get top performing brands
     */
    public function getTopBrands()
    {
        $brands = Brand::with(['products.saleItems'])
            ->get()
            ->map(function ($brand) {
                $revenue = 0;
                foreach ($brand->products as $product) {
                    $revenue += $product->saleItems->sum('total_price');
                }

                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'revenue' => $revenue
                ];
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values();

        return response()->json([
            'status' => 'success',
            'brands' => $brands
        ]);
    }

    /**
     * Get brand comparison
     */
    public function compareBrands(Request $request)
    {
        $brandIds = $request->input('brand_ids', []);

        $brands = Brand::with(['products.saleItems', 'products.productSupplies'])
            ->whereIn('id', $brandIds)
            ->get()
            ->map(function ($brand) {
                $totalSales = 0;
                $totalRevenue = 0;
                $totalProfit = 0;

                foreach ($brand->products as $product) {
                    $productSales = $product->saleItems->sum('qty');
                    $productRevenue = $product->saleItems->sum('total_price');
                    $costPrice = $product->productSupplies->avg('unit_price') ?? 0;
                    $productProfit = ($product->price - $costPrice) * $productSales;

                    $totalSales += $productSales;
                    $totalRevenue += $productRevenue;
                    $totalProfit += $productProfit;
                }

                return [
                    'name' => $brand->name,
                    'sales' => $totalSales,
                    'revenue' => $totalRevenue,
                    'profit' => $totalProfit,
                    'products' => $brand->products->count()
                ];
            });

        return response()->json([
            'status' => 'success',
            'comparison' => $brands
        ]);
    }

}
