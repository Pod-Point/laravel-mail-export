<?php

namespace PodPoint\LaravelMailExport\Traits;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Events\MailableSent;
use PodPoint\LaravelMailExport\Exportable;
use Swift_Message;

trait ExportableMail
{
    public function send(MailerContract $mailer)
    {
        if (!$this instanceof Exportable) {
            throw new \Exception('Needs to implement Exportable');
        }

        if (!$this instanceof Mailable) {
            throw new \Exception('Not mailable class');
        }

        $this->withSwiftMessage(function (Swift_Message $message){
            event(new MailableSent($this, $message));
        });

        parent::send($mailer);
    }
}
