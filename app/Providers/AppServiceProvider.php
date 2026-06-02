<?php

namespace App\Providers;

use App\Models\ModuleGroup;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('admin.layouts.app', function ($view) {
            $sidebarGroups = ModuleGroup::with(['modules' => fn ($q) => $q->where('is_shown', true)->orderBy('order')])
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            $view->with('sidebarGroups', $sidebarGroups);
        });
    }
}
