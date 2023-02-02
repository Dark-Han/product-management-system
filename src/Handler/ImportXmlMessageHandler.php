<?php

namespace App\Handler;

use App\Entity\Product;
use App\Message\ImportXmlMessage;
use App\ValueObject\ProductWeight;
use App\ValueObject\WeightUnit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use XMLReader;
use SimpleXMLElement;

#[AsMessageHandler]
class ImportXmlMessageHandler
{

    private XMLReader $XMLReader;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->XMLReader=new XMLReader();
    }

    public function __invoke(ImportXmlMessage $message)
    {
        $this->XMLReader->open($message->getXml());

        $currentRow = 1;

        while ($this->XMLReader->read()) {
            if ($this->isProductRow()) {
                $productXmlRowObject = $this->getProductXmlRowObject();

                $product = new Product();
                $product->setName((string)$productXmlRowObject->name);
                $product->setDescription((string)$productXmlRowObject->description);
                $product->setDescriptionCommon((string)$productXmlRowObject->description_common);
                $product->setDescriptionForOzon((string)$productXmlRowObject->description_for_ozon);
                $product->setDescriptionForWildberries((string)$productXmlRowObject->description_for_wildberries);
                $weight=explode(' ',$productXmlRowObject->weight);

                $product->setWeight(new ProductWeight($weight[0],WeightUnit::getCase($weight[1])));
                $product->setCategory((string)$productXmlRowObject->category);

                $this->entityManager->persist($product);

                if($currentRow%10000===0){
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
                $currentRow++;
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->XMLReader->close();
    }

    private function isProductRow(): bool
    {
        return $this->XMLReader->nodeType === $this->XMLReader::ELEMENT && $this->XMLReader->name === 'product';
    }

    private function getProductXmlRowObject(): false|SimpleXMLElement
    {
        $productXmlRow = $this->XMLReader->readOuterXml();
        return simplexml_load_string($productXmlRow, 'SimpleXMLElement', LIBXML_NOBLANKS && LIBXML_NOWARNING);
    }
}