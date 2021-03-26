<?php

namespace PodPoint\MailExport\Tests\Stubs;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class ExportableByMethodsMailable extends Mailable implements ShouldExport
{
    use Exportable;

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

    public function exportDisk(): string
    {
        return 'some_disk';
    }

    public function exportPath(): string
    {
        return 'some_path';
    }

    public function exportFilename(): string
    {
        return 'some_filename';
    }
}
