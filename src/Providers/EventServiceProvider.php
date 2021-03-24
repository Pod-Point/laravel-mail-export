<?php

namespace PodPoint\MailExport\Providers;

use Illuminate\Mail\Events\MessageSent;
use PodPoint\MailExport\Listeners\ExportMessage;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the package.
     *
     * @var array
     */
    protected $listen = [
        MessageSent::class => [
            ExportMessage::class,
        ]
    ];

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
