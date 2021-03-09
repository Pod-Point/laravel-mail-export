<?php
namespace PodPoint\LaravelMailExport\Provider;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\ServiceProvider;
use PodPoint\LaravelMailExport\Events\ExportMail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var \string[][]
     */
    protected $listen = [
        MessageSending::class => [
            ExportMail::class,
        ],
    ];
}
