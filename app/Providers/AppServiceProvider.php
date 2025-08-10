<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share data dengan semua view
        View::share('appVersion', config('app.version', '1.0.0'));
        
        // Atau khusus untuk footer
        View::composer('partials.footer', function ($view) {
            $view->with([
                'version' => config('app.version', '1.0.0'),
                'company' => 'PT PLN (Persero)',
                'year' => date('Y')
            ]);
        });
    }
}