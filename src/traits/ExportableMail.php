<?php

namespace PodPoint\LaravelMailExport\Traits;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;
use PodPoint\LaravelMailExport\Events\MailableSent;
use PodPoint\LaravelMailExport\Exportable;
use Swift_Message;

trait ExportableMail
{
    public function send(MailerContract $mailer)
    {
        if (!$this instanceof Exportable) {
            throw new MostImplementExportableException('The provided Mailable instance does not implement Exportable.');
        }

        if (!$this instanceof Mailable) {
            throw new MostBeTypeMailableException('The provided mailable instance is of type mailable.');
        }

        $this->withSwiftMessage(function (Swift_Message $message) use ($mailer) {
            Storage::disk($this->getStorageDisk())
                ->put($this->getStoragePath(), $message->toString());
        });

        parent::send($mailer);
    }
}
