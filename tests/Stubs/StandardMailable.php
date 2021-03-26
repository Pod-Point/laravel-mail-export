<?php

namespace PodPoint\MailExport\Tests\Stubs;

use Illuminate\Mail\Mailable;

class StandardMailable extends Mailable
{
    public $to = [
        ['address' => 'jane@example.com', 'name' => 'Jane Doe'],
    ];

    public function build()
    {
        $this
            ->from('john@example.com')
            ->subject('This is the subject')
            ->html('This is the html.');
    }
}
