<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\ValueObject\ProductFilter;

class ProductService{

    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProductsPerPage($page){
        return $this->productRepository->getProductsPerPage($page);
    }

    public function getProductsByFilterParams(ProductFilter $filter){
        return $this->productRepository->getProductsByFilterParams($filter);
    }

}

?>