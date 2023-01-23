<?php

namespace App\Handler;

use App\Entity\Category;
use App\Entity\Product;
use App\Message\ImportXmlMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ImportXmlMessageHandler implements MessageHandlerInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(ImportXmlMessage $message)
    {
        $products = [];

        $xml = new \XMLReader();
        $xml->open($message->getXml());

        $currentRow = 1;

        while ($xml->read()) {
            if ($xml->nodeType == \XMLReader::ELEMENT && $xml->name == 'product') {
                $productXmlRow = $xml->readOuterXml();
                $productXmlRowObject = simplexml_load_string($productXmlRow, 'SimpleXMLElement', LIBXML_NOBLANKS && LIBXML_NOWARNING);

                $product = new Product();
                $product->setName((string)$productXmlRowObject->name);
                $product->setDescription((string)$productXmlRowObject->description);
                $product->setWeight((int)$productXmlRowObject->weight);
                $product->setCategory($productXmlRowObject->category);

                $this->entityManager->persist($product);

                if($currentRow%10000===0){
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
                $currentRow++;
            }
        }

        $this->entityManager->flush();

        $xml->close();
    }
}