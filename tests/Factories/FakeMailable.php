<?php

namespace PodPoint\LaravelMailExport\Tests\Factories;

use Illuminate\Mail\Mailable;
use PodPoint\LaravelMailExport\Traits\Exportable;

class FakeMailable extends Mailable
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
}
