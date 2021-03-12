<?php
namespace PodPoint\LaravelMailExport\Tests\Factories;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Traits\ExportableMail;

class FakeConfigPropertyMailable extends Mailable
{
    use ExportableMail;

    public $storageDisk = 'someDisk';
    public $storagePath = 'some/path';

    /**
     * Stub
     *
     * @return string
     */
    public function build()
    {
        return '';
    }
}
