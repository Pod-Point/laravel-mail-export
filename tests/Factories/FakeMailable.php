<?php

namespace PodPoint\LaravelMailExport\Tests\Factories;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Traits\ExportableMail;

class FakeMailable extends Mailable
{
    use ExportableMail;

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
