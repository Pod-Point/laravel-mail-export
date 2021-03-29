<?php

namespace PodPoint\MailExport\Tests\Stubs;

use Illuminate\Mail\Mailable;

abstract class StubbedMailable extends Mailable
{
    public $to = [
        ['address' => 'jane@example.com', 'name' => 'Jane Doe'],
    ];

    public function build()
    {
        $this
            ->from('jane@example.com')
            ->subject('This is the subject');
    }

    protected function buildView()
    {
        return [
            'raw' => 'This is the body.',
        ];
    }
}
