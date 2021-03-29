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
     * @inheritDoc
     */
    public function send(MailerContract $mailer)
    {
        $this->withSwiftMessage(function ($message) {
            if (! $this instanceof ShouldExport) {
                return;
            }

            /** @var \Swift_Message $message */
            $headers = $message->getHeaders();

            $headers->addParameterizedHeader('X-Mail-Export', $message->getId(), [
                'disk' => $this->storageDisk(),
                'path' => $this->storagePath(),
                'filename' => $this->storageFilename(),
            ]);
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
        return $this->storageOption('exportDisk');
    }

    /**
     * Get the filesystem path to be used when exporting that Mailable.
     *
     * @return string|null
     */
    public function storagePath(): ?string
    {
        return $this->storageOption('exportPath');
    }

    /**
     * Get the filesystem file name to be used when exporting that Mailable.
     *
     * @return string|null
     */
    public function storageFilename(): ?string
    {
        return $this->storageOption('exportFilename');
    }

    /**
     * Tries to resolve storage options from an optional method and property.
     *
     * @param  string  $key
     * @return string|null
     */
    private function storageOption(string $key): ?string
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return property_exists($this, $key) ? $this->$key : null;
    }
}
