<?php

namespace PodPoint\MailExport;

use Swift_Message;
use Carbon\Carbon;
use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Data transfer object responsible for holding
 * storage informations when exporting a mail.
 */
class StorageOptions
{
    const EXTENSION = 'eml';

    /**
     * @var string
     */
    public $disk;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var \Swift_Message
     */
    public $message;

    public function __construct(Swift_Message $message, array $parameters = [])
    {
        $this->message = $message;

        $parameters = Arr::only($parameters, ['disk', 'path', 'filename']);

        foreach ($parameters as $propertyName => $propertyValue) {
            $default = 'default'.Str::studly($propertyName);
            $this->$propertyName = $propertyValue ?: $this->$default();
        }
    }

    public function __set($name, $value)
    {
        throw new RuntimeException("PodPoint\\MailExport\\StorageOptions data transfer objects are read only.");
    }

    /**
     * Build the default storage disk using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultDisk(): string
    {
        return config('mail-export.disk') ?: config('filesystem.default');
    }

    /**
     * Build the default storage path using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultPath(): string
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

    /**
     * Builds the full path we will store the file onto the disk.
     *
     * @return string
     */
    public function fullpath(): string
    {
        return "{$this->path}/{$this->filename}.".self::EXTENSION;
    }

    /**
     * Build some array representation of that data transfer object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'filename' => $this->filename,
            'extension' => self::EXTENSION,
            'fullpath' => $this->fullpath(),
        ];
    }
}
