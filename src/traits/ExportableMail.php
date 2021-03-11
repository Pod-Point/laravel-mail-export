<?php

namespace PodPoint\LaravelMailExport\Traits;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Mailable;
use League\Flysystem\Config;
use Swift_Message;
use PodPoint\LaravelMailExport\Exceptions\MailExportConfigNotFoundException;
use PodPoint\LaravelMailExport\Exceptions\MostBeTypeMailableException;

trait ExportableMail
{
    /**
     * Overwrite Mailable send and push a file to the storage disk.
     *
     * @param  MailerContract  $mailer
     */
    public function send(MailerContract $mailer)
    {
        if (!$this instanceof Mailable) {
            throw new MostBeTypeMailableException('The provided mailable instance is of type mailable.');
        }

        $this->withSwiftMessage(function (Swift_Message $message) use ($mailer) {

            $fileSystem = app(Filesystem::class);

            $fileSystem->disk($this->getStorageDiskConfig())
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

    /**
     * Checks where the storage path config is. Order Class Method -> Class property -> Laravel config.
     *
     * @return string
     */
    private function getStoragePathConfig(): string
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

    /**
     * Get the config value from mail-export.
     *
     * @param  string  $config
     * @return string
     */
    private function getConfig(string $config): string
    {
        $config = app(config::class);
        dd($config->get('Hello'));
        if (!empty($config->get("mail-export.storage")[get_class($this)])
            && !empty($config->get('mail-export.storage')[get_class($this)][$config])) {
            return $config->get('mail-export.storage')[get_class($this)][$config];
        }

        $className = get_class($this);

        throw new MailExportConfigNotFoundException("No {$config} config found for {$className}");
    }
}
