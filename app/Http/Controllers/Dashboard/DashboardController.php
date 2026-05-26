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
        $stats = Cache::remember('dashboard.stats', 900, function () {
            return [
                'total_products' => Product::where('is_active', true)->count(),
                'total_categories' => Category::count(),
                'new_orders' => Order::where('status', 'new')->count(),
                'total_orders' => Order::count(),
            ];
        });

        $recentOrders = Order::latest()->take(10)->get();

        return view('dashboard.index', compact('stats', 'recentOrders'));
    }
}
