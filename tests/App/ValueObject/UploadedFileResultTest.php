<?php

namespace App\Tests\App\ValueObject;

use App\ValueObject\UploadedFileResult;
use PHPUnit\Framework\TestCase;

class UploadedFileResultTest extends TestCase
{
    public function testFullyUploaded(): void
    {
        $uploadedFileResult=UploadedFileResult::fullyUploaded('testPath');
        $this->assertTrue($uploadedFileResult->isFullyUploaded());
        $this->assertIsString($uploadedFileResult->getFullyUploadedPath());
        $this->assertEquals('testPath',$uploadedFileResult->getFullyUploadedPath());
    }

    public function testNotFullyUploaded():void{
        $uploadedFileResult=UploadedFileResult::notFullyUploaded();
        $this->assertFalse($uploadedFileResult->isFullyUploaded());
        $this->assertNull($uploadedFileResult->getFullyUploadedPath());
    }
}
