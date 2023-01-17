<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileStorage{

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function upload(UploadedFile $file,string $pathToUploadFolder){
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($pathToUploadFolder, $fileName);
        } catch (FileException $e) {
            // ...
        }

        return $pathToUploadFolder."/".$fileName;
    }

}


?>