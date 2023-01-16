<?php

namespace App\Service;

use App\Repository\ProductRepository;

class ProductService{

    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getAllProducts(){
        return $this->productRepository->findAll();
    }

    public function getProductsByName(string $productName){
        return $this->productRepository->getProductsByName($productName);
    }

}

?>