<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use App\Entity\User;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    #[Route('/list/products', name: 'products_list', methods: ['GET'])]
    public function listProducts(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();

        return $this->render('product/products_list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/approve/{id}', name: 'product_approve', methods: ['GET'])]
    public function approveProduct(int $id, ProductRepository $productRepo, EntityManagerInterface $em): Response
    {
        $product = $productRepo->find($id);
        if ($product) {
            $product->setStatus('approved');
            $em->flush();
        }

        return $this->redirectToRoute('products_list');
    }

    #[Route('/product/reject/{id}', name: 'product_reject', methods: ['GET'])]
    public function rejectProduct(int $id, ProductRepository $productRepo, EntityManagerInterface $em): Response
    {
        $product = $productRepo->find($id);
        if ($product) {
            $product->setStatus('rejected');
            $em->flush();
        }

        return $this->redirectToRoute('products_list');
    }
}
