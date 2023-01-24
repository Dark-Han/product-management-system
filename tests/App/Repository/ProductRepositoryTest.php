<?php

namespace App\Tests\App\Repository;

use App\Entity\Product;
use App\Tests\AbstractRepositoryTest;
use App\ValueObject\ProductFilter;

class ProductRepositoryTest extends AbstractRepositoryTest
{
    public function testFilteringProductsByPerPage(): void
    {
        $productFilter = new ProductFilter(null, 1);
        $productRepository = $this->getRepositoryForEntity(Product::class);

        $products = $productRepository->getProductsByFilterParams($productFilter);

        $this->assertEquals(1, $products->getCurrentPageNumber());
        $this->assertEquals(100,$products->getItemNumberPerPage());
    }

    public function testFilteringProductsByName(){
        $productFilter=new ProductFilter('test1',1);
        $productRepository=$this->getRepositoryForEntity(Product::class);

        $this->createCertainCountOfProductsWithName('test1',4);
        $this->createCertainCountOfProductsWithName('test2',1);

        $products=$productRepository->getProductsByFilterParams($productFilter);
        $this->assertEquals(4,$products->getTotalItemCount());
    }

    private function createCertainCountOfProductsWithName(string $name,int $count){
        for ($i=0;$i<$count;$i++){
            $product=new Product();
            $product->setName($name);
            $product->setDescriptionForWildberries('test');
            $product->setDescription('test');
            $product->setDescriptionForOzon('test');
            $product->setDescriptionCommon('test');
            $product->setCategory('test');
            $product->setWeight('test');

            $this->em->persist($product);
        }

        $this->em->flush();
        $this->em->clear();
    }
}
