<?php
namespace PodPoint\LaravelMailExport\Provider;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use PodPoint\LaravelMailExport\Events\ExportMail;
use PodPoint\LaravelMailExport\Events\MailableSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var \string[][]
     */
    protected $listen = [
        MailableSent::class => [
            ExportMail::class,
        ],
    ];
}
