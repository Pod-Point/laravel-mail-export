<?php

namespace PodPoint\LaravelMailExport\Traits;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;
use PodPoint\LaravelMailExport\Events\MailableSent;
use PodPoint\LaravelMailExport\Exportable;
use Swift_Message;

trait ExportableMail
{
    public function send(MailerContract $mailer)
    {
        if (!$this instanceof Exportable) {
            throw new MostImplementExportableException('The provided Mailable instance does not implement Exportable.');
        }

        if (!$this instanceof Mailable) {
            throw new MostBeTypeMailableException('The provided mailable instance is of type mailable.');
        }

        $this->withSwiftMessage(function (Swift_Message $message) use ($mailer) {
            Storage::disk($this->getStorageDiskConfig())
                ->put($this->getStoragePathConfig(), $message->toString());
        });

        parent::send($mailer);
    }

    /**
     * Checks where the storage disk config is. Order Class Method -> Class property -> Laravel config.
     *
     * @return string
     */
    private function getStorageDiskConfig(): string
    {
        if (method_exists($this, 'getStorageDisk') && !empty($this->getStorageDisk())) {
            return $this->getStorageDisk();
        }

        if (property_exists($this, 'storageDisk')) {
            return $this->storageDisk;
        }

        if ($disk = $this->getConfig('disk')) {
            return $disk;
        }
    }

    private function getConfig(string $config): string
    {
        if (!empty(config("mail-export.storage")[get_class($this)])
            && !empty(config('mail-export.storage')[get_class($this)][$config])) {
            return config('mail-export.storage')[get_class($this)][$config];
        }

        return '';
    }

    /**
     * Checks where the storage path config is. Order Class Method -> Class property -> Laravel config.
     *
     * @return string
     */
    public function getStoragePathConfig(): string
    {
        if (method_exists($this, 'getStoragePath') && !empty($this->getStoragePath())) {
            return $this->getStoragePath();
        }

        if (property_exists($this, 'storagePath')) {
            return $this->storagePath;
        }

        if ($path = $this->getConfig('path')) {
            return $path;
        }
    }
}
