<?php

namespace PodPoint\MailExport\Listeners;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Mail\Events\MessageSent;
use PodPoint\MailExport\Events\MessageStored;
use PodPoint\MailExport\StorageOptions;
use Swift_Message;

class ExportMessage
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Factory
     */
    protected $filesystem;

    /**
     * Create a new listener instance.
     *
     * @param  Factory  $filesystem
     */
    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Handles the Event when it happens while listening.
     *
     * @param  MessageSent  $event
     */
    public function handle(MessageSent $event): void
    {
        if ($this->shouldStoreMessage($event->message)) {
            $this->storeMessage($event->message);
        }
    }

    /**
     * Finds out if wether we should store the mail or not.
     *
     * @param  Swift_Message  $message
     * @return bool
     */
    protected function shouldStoreMessage(Swift_Message $message): bool
    {
        return property_exists($message, '_storageOptions')
            && config('mail-export.enabled', false);
    }

    /**
     * Actually stores the stringified version of the \Swift_Message including headers,
     * recipients, subject and body onto the filesystem disk.
     *
     * @param  Swift_Message  $message
     * @return void
     */
    private function storeMessage(Swift_Message $message): void
    {
        /** @var StorageOptions $storageOptions */
        $storageOptions = $message->_storageOptions;

        $this->filesystem
            ->disk($storageOptions->disk)
            ->put($storageOptions->fullpath(), $message->toString(), [
                'mimetype' => $storageOptions::MIME_TYPE,
            ]);

        event(new MessageStored($message, $storageOptions));
    }
}
