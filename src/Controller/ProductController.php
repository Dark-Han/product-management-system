<?php

namespace App\Controller;

use App\Message\ImportXmlMessage;
use App\Service\ProductService;
use App\ValueObject\ProductFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', methods: 'GET')]
    public function index(Request $request, ProductService $productService): Response
    {
        $productFilter = new ProductFilter($request->query->get('name'), $request->query->getInt('page'));
        $products = $productService->getProductsByFilterParams($productFilter);

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/products/import', methods: 'GET')]
    public function showImportPage(Request $request, ProductService $productService): Response
    {
        return $this->render('product/import.html.twig');
    }

    #[Route('/products/import-xml', methods: 'POST')]
    public function importXml(Request $request,ProductService $productService)
    {
        $productService->importFileByChunks(
            $request->request->get('name'),
            file_get_contents($_FILES['file']['tmp_name']),
            $request->request->get('chunk'),
            $request->request->get('chunks')
        );
        return new Response();
    }

    #[Route('/test')]
    public function testRabbit(MessageBusInterface $bus,string $uploadsFolder){
        $bus->dispatch(new ImportXmlMessage($uploadsFolder.'import3.xml'));
        return new Response('Сообщение отправлено');
    }
}
