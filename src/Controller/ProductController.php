<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product')]
    public function index(ProductService $productService): Response
    {
        $products=$productService->getAllProducts();
        return $this->render('product/index.html.twig',[
            'products'=>$products
        ]);
    }

    public function filter(){

    }
}
