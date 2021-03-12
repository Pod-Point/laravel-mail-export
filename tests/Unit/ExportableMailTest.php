<?php

namespace PodPoint\LaravelMailExport\Tests\Unit;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\TestCase;
use PodPoint\LaravelMailExport\Exceptions\MailExportConfigNotFoundException;
use PodPoint\LaravelMailExport\Tests\Factories\FakeMailable;
use PodPoint\LaravelMailExport\Traits\ExportableMail;
use PodPoint\LaravelMailExport\Exceptions\MostBeTypeMailableException;
use Swift_Message;

class ExportableMailTest extends TestCase
{
    /**
     * @var FakeMailable
     */
    private $fakeMailable;

    /**
     * @var MailerContract|\PHPUnit\Framework\MockObject\MockObject
     */
    private $fakeMailer;

    /**
     * @var Swift_Message
     */
    private $fakeSwiftMessage;

    /**
     * @var Filesystem|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockFileSystem;

    /**
     * @var Config|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockConfig;

    /**
     * SetUp...
     */
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Mail::fake();

        $this->fakeMailable = new FakeMailable();
        $this->fakeMailer = $this->getMockBuilder(MailerContract::class)->getMock();
        $this->fakeSwiftMessage = new Swift_Message();

        $this->mockFileSystem = $this->getMockBuilder(Filesystem::class)->getMock();
        $this->mockConfig = $this->getMockBuilder(Config::class)->setMethods(['get'])->getMock();

        app()->instance(Filesystem::class, $this->mockFileSystem);
        app()->instance(Config::class, $this->mockConfig);
    }

    /**
     * Ensure that exception is thrown when the ExportableMail trait is added to a class that does not extend
     * Laravel Mailable.
     */
    public function testThrowsExceptionWhenExportableMailIsAddedToClassThatDoesExtendMailable()
    {
        $exportableMail = $this->getMockForTrait(ExportableMail::class);

        $this->expectException(MostBeTypeMailableException::class);

        $exportableMail->send($this->fakeMailer);
    }

    /**
     * Ensure that an exception is thrown when the Mailable call back is handled and their is no defined disk or path.
     * That being no Class method getStorageDisk, a class property storageDisk or the config file.
     */
    public function testThrowsExceptionWhenNoDiskOrPathIsDefine()
    {
        $this->fakeMailable->send($this->fakeMailer);

        $this->expectException(MailExportConfigNotFoundException::class);

        foreach ($this->fakeMailable->callbacks as $callback) {
            $callback($this->fakeSwiftMessage);
        }
    }

    /**
     * Ensure when there is no class method called getStorageDisk, getStoragePath and no class property storageDisk,
     * storagePath that we read the value from the config file (if exists).
     */
    public function testTraitReadDiskAndPathFromConfigFileWhenNoClassMethodOrProperty()
    {
        $this->fakeMailable->send($this->fakeMailer);

        $this->mockConfig->expects($this->exactly(3))->method('get')->willReturn('something');

        foreach ($this->fakeMailable->callbacks as $callback) {
            $callback($this->fakeSwiftMessage);
        }
    }
}
