<?php

namespace PodPoint\MailExport\Concerns;

use Swift_Message;
use Illuminate\Mail\Mailable;
use Illuminate\Config\Repository as Config;
use PodPoint\MailExport\Contracts\ShouldExport;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use PodPoint\MailExport\Exceptions\MostBeTypeMailableException;
use PodPoint\MailExport\Exceptions\MailExportConfigNotFoundException;

/**
 * @mixin Mailable
 */
trait Exportable
{
    /**
     * Send the message using the given mailer.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @return void
     */
    public function send(MailerContract $mailer)
    {
        $this->withSwiftMessage(function ($message) {
            $message->shouldStore = $this instanceof ShouldExport;
            $message->storageDisk = $this->storageDisk();
            $message->storagePath = $this->storagePath();
        });

        parent::send($mailer);
    }

    /**
     * Get the filesystem disk to be used when exporting that Mailable.
     *
     * @return string|null
     */
    public function storageDisk(): ?string
    {
        if (method_exists($this, 'exportDisk')) {
            return $this->exportDisk();
        }

        return property_exists($this, 'exportDisk')
            ? $this->exportDisk
            : null;
    }

    /**
     * Get the filesystem path to be used when exporting that Mailable.
     *
     * @return string|null
     */
    public function storagePath(): ?string
    {
        if (method_exists($this, 'exportPath')) {
            return $this->exportPath();
        }

        return property_exists($this, 'exportPath')
            ? $this->exportPath
            : null;
    }
}
