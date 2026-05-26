<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'   => \App\Models\Product::where('is_active', true)->count(),
            'total_categories' => \App\Models\Category::count(),
            'new_orders'       => \App\Models\Order::where('status', 'new')->count(),
            'total_orders'     => \App\Models\Order::count(),
        ];

        $recentOrders = \App\Models\Order::latest()->take(10)->get();

        return view('dashboard.index', compact('stats', 'recentOrders'));
    }
}
