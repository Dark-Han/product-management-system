<?php

namespace App\Controller;

use App\Service\ProductService;
use App\ValueObject\ProductFilter;
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
            $productFilter=new ProductFilter($request->query->get('name'),$request->query->getInt('page'));
            $products=$productService->getProductsByFilterParams($productFilter);
        }
        else{
            $products=$productService->getProductsPerPage($request->query->getInt('page'));
        }

        return $this->render('product/index.html.twig',[
            'products'=>$products
        ]);
    }

}
