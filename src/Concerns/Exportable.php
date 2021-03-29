<?php

namespace PodPoint\MailExport\Concerns;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Contracts\ShouldExport;

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
            $message->_shouldStore = $this instanceof ShouldExport;
            $message->_storageDisk = $this->storageDisk();
            $message->_storagePath = $this->storagePath();
            $message->_storageFilename = $this->storageFilename();
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

    /**
     * Get the filesystem file name to be used when exporting that Mailable.
     *
     * @return string|null
     */
    public function storageFilename(): ?string
    {
        if (method_exists($this, 'exportFilename')) {
            return $this->exportFilename();
        }

        return property_exists($this, 'exportFilename')
            ? $this->exportFilename
            : null;
    }
}
