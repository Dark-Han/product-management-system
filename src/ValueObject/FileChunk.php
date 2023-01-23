<?php

namespace App\ValueObject;

class FileChunk
{
    private string $content;

    public function __construct(
        private string   $fileName
        , private string $pathToChuck
        , private int    $serialNumber
        , private bool   $isLastChunk
    )
    {
        $this->setContent();
    }

    /**
     * @return int
     */
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

    private function setContent(){
        $this->content=file_get_contents($this->pathToChuck);
    }

}