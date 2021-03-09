<?php

namespace PodPoint\LaravelMailExport\Events;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Storage;

class ExportMail
{
    /**
     * @param  MessageSending  $mailEvent
     */
    public function handle(MessageSending $mailEvent)
    {
        if (property_exists($mailEvent->message, 'storagePath') && property_exists($mailEvent->message, 'storageDisk')) {
            $storagePath = $mailEvent->message->storagePath;
            $storageDisk = $mailEvent->message->storageDisk;

            Storage::disk($storageDisk)
                ->put($storagePath, $mailEvent->message->toString());
        }
    }
}
