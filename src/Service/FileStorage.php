<?php

namespace App\Service;

use App\ValueObject\FileChunk;
use App\ValueObject\UploadedFileResult;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileStorage
{

    public function __construct(private string $uploadsFolder, private SluggerInterface $slugger)
    {
    }

    public function upload(UploadedFile $file, string $folder): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $pathToUploadedFolder = $this->uploadsFolder . $folder;

        try {
            $file->move($pathToUploadedFolder, $fileName);
        } catch (FileException $e) {
            // ...
        }

        return $pathToUploadedFolder . "/" . $fileName;
    }

    //отрефакторить
    public function uploadFileStreamByChunks(FileChunk $fileChunk):UploadedFileResult
    {
        $filePath = $this->uploadsFolder.'/'.$fileChunk->fileName();

        $out = fopen("{$filePath}.part", $fileChunk->serialNumber() == 0 ? "w+" : "a+");
        if ($out) {
            fwrite($out, $fileChunk->content());
            fclose($out);
        } else {
            die("Failed to open output stream");
        }

        if ($fileChunk->isLastChunk()) {
            rename("{$filePath}.part", $filePath);
            return UploadedFileResult::fullyUploaded($filePath);
        }

            return UploadedFileResult::notFullyUploaded();
    }

}


?>