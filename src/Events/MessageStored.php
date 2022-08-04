<?php

namespace PodPoint\MailExport\Events;

use Illuminate\Foundation\Events\Dispatchable;
use PodPoint\MailExport\StorageOptions;
use Swift_Message;

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
     * The filesystem storage options used to store the message including
     * the disk, the path and the filename with its extension.
     *
     * @var \PodPoint\MailExport\StorageOptions
     */
    public $storageOptions;

    /**
     * Create a new event instance.
     *
     * @param  Swift_Message  $message
     * @param  StorageOptions  $storageOptions
     * @return void
     */
    public function __construct(Swift_Message $message, StorageOptions $storageOptions)
    {
        $this->message = $message;
        $this->storageOptions = $storageOptions;
    }
}
