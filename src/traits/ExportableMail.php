<?php

namespace PodPoint\LaravelMailExport\traits;

use Illuminate\Mail\Mailable;

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
        if ($mailable instanceof ExportMailable) {
            $message->storagePath = $mailable->getStoragePath();
            $message->storageDisk = $mailable->getStorageDisk();
        }
    }
}
