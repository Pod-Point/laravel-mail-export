<?php

namespace PodPoint\MailExport\Tests\Stubs;

use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class ExportableMailable extends StubbedMailable implements ShouldExport
{
    use Exportable;
}
