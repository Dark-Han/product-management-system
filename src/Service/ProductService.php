<?php

namespace App\Service;

use App\Message\ImportXmlMessage;
use App\Message\ProductsFromXmlParsed;
use App\Repository\ProductRepository;
use App\ValueObject\FileChunk;
use App\ValueObject\ProductFilter;
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

    public function importFileByChunks(string $fileName, string $content, int $chunkSerialNumber, int $totalChunksCount)
    {
        $chunk = new FileChunk($fileName
            , $content
            , $chunkSerialNumber
            , $chunkSerialNumber === $totalChunksCount - 1);

        $uploadFileResult = $this->fileStorage->uploadFileStreamByChunks($chunk);

        if ($uploadFileResult->isFullyUploaded()) {
            $this->bus->dispatch(new ImportXmlMessage($uploadFileResult->getFullyUploadedPath()));
        }

        return 'imported';
    }

}

?>