<?php

namespace PodPoint\MailExport\Listeners;

use Carbon\Carbon;
use Swift_Mime_Header;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Str;
use PodPoint\MailExport\Contracts\ShouldExport;
use PodPoint\MailExport\Events\MessageStored;

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
     * The filesystem disk, path and filename used to store the message.
     *
     * @var \Swift_Mime_Header
     */
    protected $storageOptions;

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
        return config('mail-export.enabled', false);
    }

    /**
     * Actually stores the stringified version of the \Swift_Message including headers,
     * recipients, subject and body onto the filesystem disk.
     *
     * @return void
     */
    private function storeMessage()
    {
        /** @var Swift_Mime_Header $storageOptions */
        $storageOptions = $this->message->getHeaders()->get('X-Mail-Export');

        $disk = $storageOptions->getParameter('disk') ?: $this->defaultDisk();
        $path = $storageOptions->getParameter('path') ?: $this->defaultPath();
        $filename = $storageOptions->getParameter('filename') ?: $this->defaultFilename();

        $this->filesystem
            ->disk($disk)
            ->put("{$path}/{$filename}.eml", $this->message->toString());

        event(new MessageStored($this->message, $disk, "{$path}/{$filename}.eml"));
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
     * so this can be used if none is provided by the developer.
     *
     * @return string
     */
    private function defaultFilename(): string
    {
        $recipients = array_keys($this->message->getTo());

        $to = ! empty($recipients)
            ? str_replace(['@', '.'], ['_at_', '_'], $recipients[0]).'_'
            : '';

        $subject = $this->message->getSubject();

        $timestamp = Carbon::now()->format('Y_m_d_His');

        return Str::slug("{$timestamp}_{$to}{$subject}", '_');
    }
}
