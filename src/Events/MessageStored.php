<?php

namespace PodPoint\MailExport\Events;

use Illuminate\Foundation\Events\Dispatchable;
use PodPoint\MailExport\StorageOptions;

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
     * @var StorageOptions
     */
    public $storageOptions;

    /**
     * Create a new event instance.
     *
     * @param  \Swift_Message  $message
     * @param  StorageOptions  $storageOptions
     * @return void
     */
    public function __construct($message, $storageOptions)
    {
        $this->message = $message;
        $this->storageOptions = $storageOptions;
    }
}
