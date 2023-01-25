<?php

namespace App\Tests\Service;

use App\Service\FileStorage;
use App\ValueObject\FileChunk;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class FileStorageTest extends TestCase
{
    private Filesystem $filesystem;
    private FileStorage $fileStorage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem=$this->createMock(Filesystem::class);
        $this->fileStorage=new FileStorage('test/',$this->filesystem);
    }

    public function testWhenFirstChunkShouldCreatePartOfFile(): void
    {
        $fileChunk=new FileChunk('test.xml','test',0,false);

        $this->filesystem->expects(self::once())
            ->method('dumpFile');

        $uploadedFileResult=$this->fileStorage->uploadFileStreamByChunks($fileChunk);

        $this->assertFalse($uploadedFileResult->isFullyUploaded());
        $this->assertNull($uploadedFileResult->getFullyUploadedPath());
    }

    public function testAfterFirstChunkShouldAppendChunkToPartOfFile(){
        $fileChunk=new FileChunk('test.xml','test',2,false);

        $this->filesystem->expects(self::once())
            ->method('appendToFile');

        $uploadedFileResult=$this->fileStorage->uploadFileStreamByChunks($fileChunk);

        $this->assertFalse($uploadedFileResult->isFullyUploaded());
        $this->assertNull($uploadedFileResult->getFullyUploadedPath());
    }

    public function testLastChunkShouldCreateFullyFile(){
        $fileChunk=new FileChunk('test.xml','test',2,true);

        $this->filesystem->expects(self::once())
            ->method('appendToFile');

        $this->filesystem->expects(self::once())
            ->method('rename');

        $uploadedFileResult=$this->fileStorage->uploadFileStreamByChunks($fileChunk);

        $this->assertTrue($uploadedFileResult->isFullyUploaded());
        $this->assertStringEndsWith('test.xml',$uploadedFileResult->getFullyUploadedPath());
    }
}
