<?php

namespace PodPoint\LaravelMailExport\Tests\Unit;

use Illuminate\Contracts\Filesystem\Filesystem;
use PodPoint\LaravelMailExport\Tests\Factories\FakeConfigMethodMailable;
use PodPoint\LaravelMailExport\Tests\Factories\FakeConfigPropertyMailable;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\TestCase;
use PodPoint\LaravelMailExport\Exceptions\MailExportConfigNotFoundException;
use PodPoint\LaravelMailExport\Tests\Factories\FakeMailable;
use PodPoint\LaravelMailExport\Traits\Exportable;
use PodPoint\LaravelMailExport\Exceptions\MostBeTypeMailableException;
use Swift_Message;

class ExportableTest extends TestCase
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
    private $mockFileSystemFactory;

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

        $this->mockFileSystemFactory = $this->getMockBuilder(Factory::class)->getMock();
        $this->mockFileSystem = $this->getMockBuilder(Filesystem::class)->getMock();

        $this->mockConfig = $this->getMockBuilder(Config::class)
            ->setMethods(['get'])
            ->getMock();

        app()->instance(Factory::class, $this->mockFileSystemFactory);
        app()->instance(Config::class, $this->mockConfig);
    }

    /**
     * Ensure that exception is thrown when the ExportableMail trait is added to a class that does not extend
     * Laravel Mailable.
     */
    public function testThrowsExceptionWhenExportableMailIsAddedToClassThatDoesExtendMailable()
    {
        $exportableMail = $this->getMockForTrait(Exportable::class);

        $this->expectException(MostBeTypeMailableException::class);

        $exportableMail->send($this->fakeMailer);
    }

    /**
     * Ensure that an exception is thrown when the Mailable call back is handled and their is no defined disk or path.
     * That being no Class method getStorageDisk, a class property storageDisk or the config file.
     */
    public function testThrowsExceptionWhenNoDiskOrPathIsDefined()
    {
        $this->fakeMailable->send($this->fakeMailer);

        $this->expectException(MailExportConfigNotFoundException::class);

        foreach ($this->fakeMailable->callbacks as $callback) {
            $callback($this->fakeSwiftMessage);
        }
    }

    /**
     * Ensure that the traits reads from class property when it is defined.
     *
     * @throws MostBeTypeMailableException
     */
    public function testTraitReadsDiskAndPathFromClassPropertyWhenDefined()
    {
        $fakeMailableClassProperties = new FakeConfigPropertyMailable();
        $fakeMailableClassProperties->storageDisk = 'someDisk';
        $fakeMailableClassProperties->storagePath = 'some/path';

        $this->mockFileSystem('someDisk', 'some/path');

        $fakeMailableClassProperties->send($this->fakeMailer);

        foreach ($fakeMailableClassProperties->callbacks as $callback) {
            $callback($this->fakeSwiftMessage);
        }
    }

    /**
     * Ensure that the traits reads from class method when it is defined.
     *
     * @throws MostBeTypeMailableException
     */
    public function testTraitReadsDiskAndPathFromClassMethodWhenDefined()
    {
        $fakeMailableClassProperties = new FakeConfigMethodMailable();

        $this->mockFileSystem('someDisk', 'some/path');

        $fakeMailableClassProperties->send($this->fakeMailer);

        foreach ($fakeMailableClassProperties->callbacks as $callback) {
            $callback($this->fakeSwiftMessage);
        }
    }

    /**
     * Ensure when there is no class method called getStorageDisk, getStoragePath and no class property storageDisk,
     * storagePath that we read the value from the config file.
     */
    public function testTraitReadDiskAndPathFromConfigFileWhenNoClassMethodOrProperty()
    {
        $this->fakeMailable->send($this->fakeMailer);

        $this->mockConfig
            ->expects($this->any())
            ->method('get')
            ->willReturn([
                get_class($this->fakeMailable) => [
                    'disk' => 'someDisk',
                    'path' => 'some/path',
                ],
            ]);

        $this->mockFileSystem('someDisk', 'some/path');

        foreach ($this->fakeMailable->callbacks as $callback) {
            $callback($this->fakeSwiftMessage);
        }
    }

    /**
     * Creates the FileSystem mock where diskToCheck and pathToCheck are checked with the inputs for disk and put methods.
     *
     * @param  string  $disk
     * @param  string  $path
     */
    public function mockFileSystem(string $diskToCheck, string $pathToCheck)
    {
        $this->mockFileSystemFactory
            ->expects($this->any())
            ->method('disk')
            ->with(
                $this->equalTo($diskToCheck)
            )->willReturn($this->mockFileSystem);

        $this->mockFileSystem
            ->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo($pathToCheck)
            );
    }
}