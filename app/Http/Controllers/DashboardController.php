<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\ProductCategory;
use App\Models\Product;

class DashboardController extends Controller
{

    public function index()
    {

        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            $sales = Sale::with(['cashier', 'saleItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();
            return view('Dashboard.super', compact('sales'));
        }

        if ($user->hasRole('admin')) {
            $sales = Sale::with(['cashier', 'saleItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();
            return view('Dashboard.admin', compact('sales'));
        }

        if ($user->hasRole('cashier')) {
            $sales = Sale::with([
                'cashier',
                'saleItems.product'
                ])
                ->where('cashier_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('Dashboard.cashier', compact('sales'));
        }

        if ($user->hasRole('store-manager')) {
            $products = Product::with(['category', 'brand', 'images'])
            ->latest()
            ->get();

            $categories = ProductCategory::all();
            return view('Dashboard.store', compact('products', 'categories'));
        }

        abort(403); // no role? no access

    }

}
