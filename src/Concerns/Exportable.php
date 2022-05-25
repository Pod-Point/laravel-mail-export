<?php

namespace PodPoint\MailExport\Concerns;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Contracts\ShouldExport;
use PodPoint\MailExport\StorageOptions;

/**
 * @mixin Mailable
 */
trait Exportable
{
    /**
     * @inheritDoc
     */
    public function send($mailer): void
    {
        $this->withSwiftMessage(function ($message) {
            if (! $this instanceof ShouldExport) {
                return;
            }

            $message->_storageOptions = new StorageOptions($message, [
                'disk' => $this->exportOption('exportDisk'),
                'path' => $this->exportOption('exportPath'),
                'filename' => $this->exportOption('exportFilename'),
            ]);
        });

        parent::send($mailer);
    }

    /**
     * Tries to resolve storage options from an optional method and property.
     *
     * @param  string  $key
     * @return string|null
     */
    private function exportOption(string $key): ?string
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return property_exists($this, $key) ? $this->$key : null;
    }
}
