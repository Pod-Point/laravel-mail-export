<?php

namespace PodPoint\MailExport\Tests\Stubs;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class ExportableByPropertiesMailable extends Mailable implements ShouldExport
{
    use Exportable;

    public $exportDisk = 'some_disk';

    public $exportPath = 'some_path';

    public $exportFilename = 'some_filename';

    public $to = [
        ['address' => 'jane@example.com', 'name' => 'Jane Doe'],
    ];

    public function build()
    {
        $this
            ->from('jane@example.com')
            ->subject('This is the subject')
            ->html('This is the html.');
    }
}
