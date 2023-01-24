<?php

namespace App\Tests\App\ValueObject;

use App\ValueObject\ProductFilter;
use PHPUnit\Framework\TestCase;

class ProductFilterTest extends TestCase
{
    public function testGetName(): void
    {
        $productFilter = new ProductFilter('test', 1);

        $this->assertEquals('test', $productFilter->getName());
    }

    public function testZeroGivenPageShouldBecomeTheFirstPage()
    {
        $productFilter = new ProductFilter('test',0);
        $this->assertEquals(1,$productFilter->getPage());
    }

    public function testGetPage(){
        $productFilterWithFirstPage=new ProductFilter('test',1);
        $productFilterWithSecondPage=new ProductFilter('test',2);
        $productFilterWithThirdPage=new ProductFilter('test',3);

        $this->assertEquals(1,$productFilterWithFirstPage->getPage());
        $this->assertEquals(2,$productFilterWithSecondPage->getPage());
        $this->assertEquals(3,$productFilterWithThirdPage->getPage());
    }
}
