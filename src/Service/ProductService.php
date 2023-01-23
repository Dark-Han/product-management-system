<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Message\ImportXmlMessage;
use App\Message\ProductsFromXmlParsed;
use App\Repository\ProductRepository;
use App\ValueObject\FileChunk;
use App\ValueObject\ProductFilter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductService
{

    public function __construct(
        private ProductRepository     $productRepository
        , private FileStorage         $fileStorage
        , private MessageBusInterface $bus
    )
    {
    }

    public function getProductsByFilterParams(ProductFilter $filter)
    {
        return $this->productRepository->getProductsByFilterParams($filter);
    }

    public function importFileByChunks(string $fileName, string $pathToChuck, int $chuckSerialNumber, int $totalChucksCount)
    {
        $chunk = new FileChunk($fileName
            , $pathToChuck
            , $chuckSerialNumber
            , $chuckSerialNumber === $totalChucksCount - 1);

        $uploadFileResult = $this->fileStorage->uploadFileStreamByChunks($chunk);

        if ($uploadFileResult->isFullyUploaded()) {
            $this->bus->dispatch(new ImportXmlMessage($uploadFileResult->getFullyUploadedPath()));
        }

        return 'imported';
    }

}

?>