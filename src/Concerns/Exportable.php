<?php

namespace PodPoint\MailExport\Concerns;

use Carbon\Carbon;
use Swift_Message;
use Illuminate\Support\Str;
use PodPoint\MailExport\StorageOptions;
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
     * @param  string|null  $default
     * @return string|null
     */
    private function exportOption(string $key, ?string $default = null): ?string
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return property_exists($this, $key) ? $this->$key : $default;
    }
}
