<?php

namespace App\Tests\Handler;

use App\Entity\Product;
use App\Handler\ImportXmlMessageHandler;
use App\Message\ImportXmlMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImportXmlMessageHandlerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function testWhenXmlParsedShouldBeWrittenToDB(): void
    {
        $message = new ImportXmlMessage('/app/tests/Public/uploads/import.xml');
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $productRepository = $em->getRepository(Product::class);
        $handler = new ImportXmlMessageHandler($em);

        $handler($message);

        $products = $productRepository->findAll();

        $this->assertEquals(3, count($products));
        foreach ($products as $product) {
            $this->assertNotEmpty($product->getName());
            $this->assertNotEmpty($product->getCategory());
            $this->assertNotEmpty($product->getDescription());
            $this->assertNotEmpty($product->getWeight());
        }
    }
}
