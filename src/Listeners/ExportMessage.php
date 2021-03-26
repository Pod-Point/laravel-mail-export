<?php

namespace PodPoint\MailExport\Listeners;

use Carbon\Carbon;
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
     * @var \Swift_Message
     */
    protected $message;

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
     * The filesystem filename used to store the message.
     *
     * @var string
     */
    public $filename;

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

        $this->shouldStore = $this->message->_shouldStore ?? false;
        $this->disk = $this->message->_storageDisk ?? $this->defaultDisk();
        $this->path = $this->message->_storagePath ?? $this->defaultPath();
        $this->filename = $this->message->_storageFilename ?? $this->defaultFilename();

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
        return $this->shouldStore
            && config('mail-export.enabled');
    }

    /**
     * Actually stores the stringified version of the \Swift_Message including headers,
     * recipients, subject and body onto the filesystem disk.
     *
     * @return void
     */
    private function storeMessage()
    {
        $this->filesystem
            ->disk($this->disk)
            ->put("{$this->path}/{$this->filename}.eml", $this->message->toString());

        event(new MessageStored($this->message, $this->disk, $this->path));
    }

    /**
     * Build the default storage disk using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultDisk()
    {
        return config('mail-export.disk', config('filesystem.default'));
    }

    /**
     * Build the default storage path using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultPath()
    {
        return config('mail-export.path');
    }

    /**
     * Build some default value for the filename of the message we're about to store
     * so this can be used if none is provided by the developer from the Mailable.
     *
     * @return string
     */
    private function defaultFilename(): string
    {
        if ($filename = config('mail-export.filename')) {
            return $filename;
        }

        $recipients = array_keys($this->message->getTo());

        $to = ! empty($recipients)
            ? str_replace(['@', '.'], ['_at_', '_'], $recipients[0]) . '_'
            : '';

        $subject = $this->message->getSubject();

        $timestamp = Carbon::now()->format('Y_m_d_His');

        return Str::slug("{$timestamp}_{$to}{$subject}", '_');
    }
}
