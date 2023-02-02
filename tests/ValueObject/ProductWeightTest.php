<?php

namespace App\Tests\ValueObject;

use App\ValueObject\ProductWeight;
use App\ValueObject\WeightUnit;
use PHPUnit\Framework\TestCase;

class ProductWeightTest extends TestCase
{

    public function testWeightGivenInKg(){
        $productWeight=new ProductWeight(5,WeightUnit::kg);
        $this->assertEquals(5000,$productWeight->getWeightInGrams());
    }

    public function testWeightGivenInGrams(){
        $productWeight=new ProductWeight(5,WeightUnit::g);
        $this->assertEquals(5,$productWeight->getWeightInGrams());
    }

}
