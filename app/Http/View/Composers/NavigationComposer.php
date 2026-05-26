<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $navCategories = Cache::remember('nav.categories', 3600, function () {
            return Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });

        $view->with('navCategories', $navCategories);
    }
}
