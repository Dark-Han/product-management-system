<?php

namespace App\ValueObject;

use ReflectionEnum;

enum WeightUnit: int
{
    case g=1;
    case kg=1000;

    /**
     * @throws \ReflectionException
     */
    public static function getCase(string $case): WeightUnit
    {
       return (new ReflectionEnum(__CLASS__))->getCase($case)->getValue();
    }

    public function getInGrams():int{
        return $this->value;
    }
}