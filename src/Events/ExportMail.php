<?php

namespace PodPoint\LaravelMailExport\Events;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

class ExportMail
{
    /**
     * @param  Mailable  $mailableSent
     */
    public function handle(MailableSent $mailableSent)
    {
        Storage::disk($mailableSent->mailable->getStorageDisk())
            ->put($mailableSent->mailable->getStoragePath(), $mailableSent->message->toString());
    }
}
