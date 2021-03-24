<?php

namespace PodPoint\MailExport\Listeners;

use Swift_Message;
use Illuminate\Support\Str;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Contracts\Filesystem\Factory;
use PodPoint\MailExport\Events\MessageStored;
use PodPoint\MailExport\Contracts\ShouldExport;

class ExportMessage
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Factory
     */
    protected $filesystem;

    /**
     * Wether a message should be exported or not based on if wether
     * it implements the ShouldExport interface or not.
     *
     * @var bool
     */
    public $shouldExport;

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
        $this->shouldStore = $event->message->shouldExport ?? false;

        $this->disk = $event->message->storageDisk ?? $this->defaultStorageDisk();

        $this->path = $event->message->storagePath ?? $this->defaultStoragePath();

        if ($this->shouldStoreMessage()) {
            $this->storeMessage($event->message);
        }
    }

    /**
     * Finds out if wether we should store the mail or not.
     *
     * @return bool
     */
    protected function shouldStoreMessage(): bool
    {
        return $this->shouldStore
            && config('mail-export.enabled');
    }

    /**
     * Actually stores the stringified version of the \Swift_Message including headers,
     * recipients, subject and body onto the filesystem disk.
     *
     * @param  \Swift_Message  $message
     * @return void
     */
    private function storeMessage(Swift_Message $message)
    {
        logger()->info('Storing message...', [
            'disk' => $this->disk,
            'path' => $this->path,
            'message' => $message->toString(),
        ]);

        // $this->filesystem
        //     ->disk($this->disk)
        //     ->put($this->path, $message->toString());
        //
        // event(new MessageStored($message, $this->disk, $this->path));
    }

    /**
     * Build the default storage disk using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultStorageDisk()
    {
        return config('mail-export.disk', config('filesystem.default'));
    }

    /**
     * Build the default storage path using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultStoragePath()
    {
        $storageFilepath = config('mail-export.path');

        return "{$storageFilepath}/{$this->defaultStorageFilename()}.eml";
    }

    /**
     * Build some default value for the filename of the message we're about to store
     * so this can be used if none is provided by the developer from the Mailable.
     *
     * @return string
     */
    private function defaultStorageFilename(): string
    {
        $recipients = array_keys($this->getSwiftMessage()->getTo());

        $to = ! empty($recipients)
            ? str_replace(['@', '.'], ['_at_', '_'], $recipients[0]) . '_'
            : '';

        $subject = $this->getSwiftMessage()->getSubject();

        $timestamp = $this->getSwiftMessage()->getDate();

        return Str::slug("{$timestamp}_{$to}{$subject}", '_');
    }
}
