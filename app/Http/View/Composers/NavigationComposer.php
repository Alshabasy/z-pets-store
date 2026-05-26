<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(\Illuminate\View\View $view): void
    {
        $navCategories = \App\Models\Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $newOrdersCount = \App\Models\Order::where('status', 'new')->count();

        $view->with(compact('navCategories', 'newOrdersCount'));
    }
}
