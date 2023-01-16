<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product')]
    public function index(Request $request,ProductService $productService): Response
    {
        if (!is_null($request->query->get('name'))){
            $products=$productService->getProductsByName($request->query->get('name'));
        }
        else{
            $products=$productService->getAllProducts();
        }

        return $this->render('product/index.html.twig',[
            'products'=>$products
        ]);
    }

}
