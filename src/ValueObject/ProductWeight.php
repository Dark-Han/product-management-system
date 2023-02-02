<?php

namespace App\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductWeight
{

    #[ORM\Column(type: Types::INTEGER)]
    private int $weight_in_grams;

    public function __construct(private int $weightInOwnUnit, private WeightUnit $unit)
    {
        $this->setWeightInGramsDependingOnUnit();
    }

    public function getWeightInGrams(): int
    {
        return $this->weight_in_grams;
    }

    private function setWeightInGramsDependingOnUnit(): void
    {
        $this->weight_in_grams = $this->weightInOwnUnit * $this->unit->getInGrams();
    }
}