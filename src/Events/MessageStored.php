<?php

namespace PodPoint\MailExport\Events;

class MessageStored
{
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
     * The filesystem path used to store the message.
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

