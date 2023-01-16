<?php

namespace App\Service;

use App\Repository\ProductRepository;

class ProductService{

    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProductsPerPage($page){
        return $this->productRepository->getProductsPerPage($page);
    }

    public function getProductsByName(string $productName){
        return $this->productRepository->getProductsByName($productName);
    }

}

?>