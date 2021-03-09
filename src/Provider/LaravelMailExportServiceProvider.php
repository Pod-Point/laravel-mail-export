<?php

namespace PodPoint\LaravelMailExport\Provider;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\ServiceProvider;

class LaravelMailExportServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageSending::class => [
            ExportMail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
