<?php

namespace PodPoint\MailExport\Tests\Stubs;

use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class ExportableByPropertiesMailable extends StubbedMailable implements ShouldExport
{
    use Exportable;

    public $exportDisk = 'some_disk';

    public $exportPath = 'some_path';

    public $exportFilename = 'some_filename';
}
