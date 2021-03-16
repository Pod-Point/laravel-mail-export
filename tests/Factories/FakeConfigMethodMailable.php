<?php

namespace PodPoint\LaravelMailExport\Tests\Factories;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Traits\Exportable;

class FakeConfigMethodMailable extends Mailable
{
    use Exportable;

    /**
     * Stub
     *
     * @return string
     */
    public function build()
    {
        return '';
    }

    /**
     * A faked getStorageDisk method.
     *
     * @return string
     */
    public function getStorageDisk()
    {
        return 'someDisk';
    }

    /**
     * A faked getStoragePath method.
     *
     * @return string
     */
    public function getStoragePath()
    {
        return 'some/path';
    }
}
