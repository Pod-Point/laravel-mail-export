<?php

namespace PodPoint\EmailArchive;

use Illuminate\Support\ServiceProvider;

class EmailArchiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/email-archive.php' => config_path('email-archive.php'),
        ], 'reviews-config');
    }
}
