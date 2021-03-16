<?php

namespace PodPoint\LaravelMailExport\Provider;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\ServiceProvider;

class MailExportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/mail-export.php' => config_path('mail-export.php'),
        ], 'mail-export');
    }
}
