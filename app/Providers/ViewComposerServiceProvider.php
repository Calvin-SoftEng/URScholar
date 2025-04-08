<?php

namespace App\Providers;

use App\Models\PortalBranding;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share portal branding with all views
        View::composer('*', function ($view) {
            $portalBranding = PortalBranding::where('status', 'Active')->first();
            $view->with('portalBranding', $portalBranding);
        });
    }
}