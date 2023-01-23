<?php

namespace App\ValueObject;

class UploadedFileResult{

    public function __construct(private bool $isFullUploaded,private string $uploadedPath)
    {
    }

    /**
     * @return bool
     */
    public function isFullUploaded(): bool
    {
        return $this->isFullUploaded;
    }

    /**
     * @return string
     */
    public function getUploadedPath(): string
    {
        return $this->uploadedPath;
    }

}