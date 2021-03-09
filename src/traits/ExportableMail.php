<?php

namespace PodPoint\LaravelMailExport\Traits;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Exportable;
use Swift_Message;

trait ExportableMail
{
    /**
     * Set up the Swift_Message object to contain the details about storing the mail.
     *
     * @param  Swift_Message  $message
     * @param  ExportMailable|Mailable  $mailable
     */
    public function setUpExportable(Swift_Message $message, $mailable)
    {
        if ($mailable instanceof Exportable) {
            $message->storagePath = $mailable->getStoragePath();
            $message->storageDisk = $mailable->getStorageDisk();
        }
    }
}
