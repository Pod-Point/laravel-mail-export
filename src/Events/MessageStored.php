<?php

namespace PodPoint\MailExport\Events;

use Illuminate\Foundation\Events\Dispatchable;
use PodPoint\MailExport\StorageOptions;
use Symfony\Component\Mime\Email;

class MessageStored
{
    use Dispatchable;

    /**
     * The message instance.
     *
     * @var \Symfony\Component\Mime\Email
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
     * @param  Email  $message
     * @param  StorageOptions  $storageOptions
     * @return void
     */
    public function __construct(Email $message, StorageOptions $storageOptions)
    {
        $this->message = $message;
        $this->storageOptions = $storageOptions;
    }
}
