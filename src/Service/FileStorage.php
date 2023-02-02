<?php

namespace App\Service;

use App\ValueObject\FileChunk;
use App\ValueObject\UploadedFileResult;
use Symfony\Component\Filesystem\Filesystem;

class FileStorage
{

    public function __construct(private string $uploadsFolder,private Filesystem $filesystem)
    {
    }

    public function uploadFileStreamByChunks(FileChunk $fileChunk):UploadedFileResult
    {
        $filePath = $this->uploadsFolder.$fileChunk->fileName();

        if ($fileChunk->isFirstChunk()){
            $this->filesystem->dumpFile("$filePath.part",$fileChunk->content());
        }else{
            $this->filesystem->appendToFile("$filePath.part",$fileChunk->content());
        }

        if ($fileChunk->isLastChunk()) {
            $this->filesystem->rename("$filePath.part", $filePath);
            return UploadedFileResult::fullyUploaded($filePath);
        }
            return UploadedFileResult::notFullyUploaded();
    }

}


?>