<?php

namespace PodPoint\MailExport\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use PodPoint\MailExport\Listeners\ExportMessage;

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
        ],
    ];
}
