<?php

namespace PodPoint\MailExport\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MessageStored
{
    use Dispatchable;

    /**
     * The Swift message instance.
     *
     * @var \Swift_Message
     */
    public $message;

    /**
     * The filesystem disk used to store the message.
     *
     * @var string
     */
    public $disk;

    /**
     * The filesystem full path used to store the message
     * including the filename and its extension.
     *
     * @var string
     */
    public $path;

    /**
     * Create a new event instance.
     *
     * @param  \Swift_Message  $message
     * @param  string  $disk
     * @param  string  $path
     * @return void
     */
    public function __construct($message, $disk, $path)
    {
        $this->message = $message;
        $this->disk = $disk;
        $this->path = $path;
    }
}
