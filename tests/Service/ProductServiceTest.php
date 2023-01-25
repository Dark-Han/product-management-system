<?php

namespace App\Tests\Service;

use App\Message\ImportXmlMessage;
use App\Repository\ProductRepository;
use App\Service\FileStorage;
use App\Service\ProductService;
use App\ValueObject\UploadedFileResult;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductServiceTest extends TestCase
{

    private ProductService $productService;

    private ProductRepository $productRepository;
    private FileStorage $fileStorage;
    private MessageBusInterface $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository=$this->createMock(ProductRepository::class);
        $this->fileStorage=$this->createMock(FileStorage::class);
        $this->bus=$this->createMock(MessageBusInterface::class);

        $this->productService=new ProductService($this->productRepository,$this->fileStorage,$this->bus);
    }

    public function testWhenFileFullyUploadedShouldDispatchMessage(): void
    {
        $this->fileStorage->expects(self::once())
            ->method('uploadFileStreamByChunks')
            ->willReturn(UploadedFileResult::fullyUploaded('test'));

        $this->bus->expects(self::once())
            ->method('dispatch')
            ->willReturn(new Envelope(new ImportXmlMessage('test')));

        $this->productService->importFileByChunks('test', 'test', 1, 2);
    }
}
