<?php

namespace App\ValueObject;

class UploadedFileResult{

    private function __construct(private bool $isFullyUploaded, private ?string $fullyUploadedPath)
    {
    }

    public static function fullyUploaded(string $uploadedPath):UploadedFileResult{
        return new UploadedFileResult(true,$uploadedPath);
    }

    public static function notFullyUploaded():UploadedFileResult{
        return new UploadedFileResult(false,null);
    }

    public function isFullyUploaded(): bool
    {
        return $this->isFullyUploaded;
    }

    public function getFullyUploadedPath(): ?string
    {
        return $this->fullyUploadedPath;
    }

}