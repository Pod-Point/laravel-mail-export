<?php

namespace PodPoint\MailExport\Tests\Stubs;

use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class ExportableByMethodsMailable extends StubbedMailable implements ShouldExport
{
    use Exportable;

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
