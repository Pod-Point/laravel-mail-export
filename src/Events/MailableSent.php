<?php
namespace PodPoint\LaravelMailExport\Events;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Exportable;

class MailableSent
{
    /**
     * @var Mailable|Exportable
     */
    public $mailable;
    /**
     * @var Swift_Message
     */
    public $message;

    /**
     * MailableSent constructor.
     * @param  Mailable  $mailable
     */
    public function __construct(Mailable $mailable, \Swift_Message $message)
    {
        $this->mailable = $mailable;
        $this->message = $message;
    }
}
