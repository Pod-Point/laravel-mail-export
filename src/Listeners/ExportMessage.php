<?php

namespace PodPoint\MailExport\Listeners;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Mail\Events\MessageSent;
use PodPoint\MailExport\Events\MessageStored;
use PodPoint\MailExport\StorageOptions;

class ExportMessage
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Factory
     */
    protected $filesystem;

    /**
     * @var \Swift_Message
     */
    protected $message;

    /**
     * Create a new listener instance.
     *
     * @param Factory $filesystem
     */
    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Handles the Event when it happens while listening.
     *
     * @param MessageSent $event
     */
    public function handle(MessageSent $event)
    {
        $this->message = $event->message;

        if ($this->shouldStoreMessage()) {
            $this->storeMessage();
        }
    }

    /**
     * Finds out if wether we should store the mail or not.
     *
     * @return bool
     */
    protected function shouldStoreMessage(): bool
    {
        return property_exists($this->message, '_storageOptions')
            && config('mail-export.enabled', false);
    }

    /**
     * Actually stores the stringified version of the \Swift_Message including headers,
     * recipients, subject and body onto the filesystem disk.
     *
     * @return void
     */
    private function storeMessage()
    {
        /** @var StorageOptions $storageOptions */
        $storageOptions = $this->message->_storageOptions;

        $this->filesystem
            ->disk($storageOptions->disk)
            ->put($storageOptions->fullpath(), $this->message->toString(), [
                'mimetype' => $storageOptions::MIME_TYPE,
            ]);

        event(new MessageStored($this->message, $storageOptions));
    }
}
