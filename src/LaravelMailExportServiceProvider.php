<?php

namespace PodPoint\LaravelMailExport;

use Illuminate\Support\ServiceProvider;

class LaravelMailExportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/mail-export.php' => config_path('mail-export.php'),
        ], 'reviews-config');
    }
}
