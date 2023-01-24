<?php

namespace App\ValueObject;

class FileChunk
{

    public function __construct(
        private string   $fileName
        , private string $content
        , private int    $serialNumber
        , private bool   $isLastChunk
    )
    {
    }

    public function serialNumber(): int
    {
        return $this->serialNumber;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }


    public function content(): string
    {
        return $this->content;
    }

    public function isLastChunk(): bool
    {
        return $this->isLastChunk;
    }

}