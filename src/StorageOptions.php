<?php

namespace PodPoint\MailExport;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;

/**
 * Data transfer object responsible for holding
 * storage information when exporting a mail.
 */
class StorageOptions
{
    const EXTENSION = 'eml';
    const MIME_TYPE = 'message/rfc822';

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
     * @var \Symfony\Component\Mime\Email
     */
    public $message;

    /**
     * Declares the storage options for a specific \Symfony\Component\Mime\Email.
     * The only properties allowed are 'disk', 'path' and 'filename'. They all
     * are obviously optional.
     *
     * @param  Email  $message
     * @param  array  $properties
     */
    public function __construct(Email $message, array $properties = [])
    {
        $this->message = $message;

        $properties = Arr::only($properties, ['disk', 'path', 'filename']);

        foreach ($properties as $propertyName => $propertyValue) {
            $default = 'default'.Str::studly($propertyName);
            $this->$propertyName = $propertyValue ?: $this->$default();
        }
    }

    /**
     * Build the default storage disk using the config if none is provided by the developer.
     *
     * @return string
     */
    private function defaultDisk(): string
    {
        return config('mail-export.disk') ?: config('filesystems.default');
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
        /** @var \Symfony\Component\Mime\Address[] */
        $recipients = $this->message->getTo();

        $to = ! empty($recipients)
            ? str_replace(['@', '.'], ['_at_', '_'], $recipients[0]->getAddress()).'_'
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
