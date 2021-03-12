<?php

namespace PodPoint\LaravelMailExport\Tests\Factories;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Traits\ExportableMail;

class FakeConfigPropertyMailable extends Mailable
{
    use ExportableMail;

    /**
     * Some value set by test mock.
     *
     * @var string
     */
    public $storageDisk;

    /**
     * Some value set by test mock.
     *
     * @var string
     */
    public $storagePath;

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
