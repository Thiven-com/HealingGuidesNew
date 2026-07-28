<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
            $uriSlug = 0;

            if (isset(\Request::route()->uri)) {
                $uriSlug = \Request::route()->uri;
            } else {
                $uriSlug = 0;
            }
            $site = SiteSetting::first();
            $view->with(['site' => $site, 'uriSlug' => $uriSlug]);
        });
    }
}
