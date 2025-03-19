<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use App\Repository\ProductRepository;

class ShopController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    
    #[Route("/shop", name:"shop")]

    public function index(): Response
    {
         // Fetch all products from the database
    $products = $this->productRepository->findAll();

    // Pass products to the Twig template
    return $this->render('pages/shop.html.twig', [
        'products' => $products,
    ]);
    }
}
