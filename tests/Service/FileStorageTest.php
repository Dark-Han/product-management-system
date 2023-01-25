<?php

namespace App\Tests\Service;

use App\Service\FileStorage;
use App\ValueObject\FileChunk;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class FileStorageTest extends KernelTestCase
{

    private readonly string $uploadsFolder;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->uploadsFolder=static::$kernel->getProjectDir().'/tests/Uploads/';
    }

    public function testWhenFirstChunkShouldCreatePartOfFile(): void
    {
        $fileChunk=new FileChunk('test.xml','test',0,false);

        $fileSystem=$this->createMock(Filesystem::class);

        $fileSystem->expects(self::once())
                    ->method('dumpFile');

        static::getContainer()->set(Filesystem::class,$fileSystem);

        $fileStorage=static::getContainer()->get(FileStorage::class);

        $uploadedFileResult=$fileStorage->uploadFileStreamByChunks($fileChunk);

        $this->assertFalse($uploadedFileResult->isFullyUploaded());
        $this->assertNull($uploadedFileResult->getFullyUploadedPath());
    }

    public function testAfterFirstChunkShouldAppendChunkToPartOfFile(){
        $fileChunk=new FileChunk('test.xml','test',2,false);

        $fileSystem=$this->createMock(Filesystem::class);

        $fileSystem->expects(self::once())
            ->method('appendToFile');

        static::getContainer()->set(Filesystem::class,$fileSystem);

        $fileStorage=static::getContainer()->get(FileStorage::class);

        $uploadedFileResult=$fileStorage->uploadFileStreamByChunks($fileChunk);

        $this->assertFalse($uploadedFileResult->isFullyUploaded());
        $this->assertNull($uploadedFileResult->getFullyUploadedPath());
    }

    public function testLastChunkShouldCreateFullyFile(){
        $fileChunk=new FileChunk('test.xml','test',2,true);

        $fileSystem=$this->createMock(Filesystem::class);

        $fileSystem->expects(self::once())
            ->method('appendToFile');

        $fileSystem->expects(self::once())
            ->method('rename');

        static::getContainer()->set(Filesystem::class,$fileSystem);

        $fileStorage=static::getContainer()->get(FileStorage::class);

        $uploadedFileResult=$fileStorage->uploadFileStreamByChunks($fileChunk);

        $this->assertTrue($uploadedFileResult->isFullyUploaded());
        $this->assertStringEndsWith('test.xml',$uploadedFileResult->getFullyUploadedPath());
    }
}
