<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Business;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_BUSINESS')]
class BusinessDashboardController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route("/dashboard_business", name:"dashboard_business")]
    public function businessDashboard(): Response
    {
        /** @var Business $business */
        $business = $this->security->getUser();
        if (!$business) {
            throw $this->createAccessDeniedException('User not authenticated');
        }
        $products = $business->getProducts();

        return $this->render('dashboards/dashboard_business.html.twig', [
            'products' => $products,
            'business' => $business,
        ]);
    }

    #[Route("/business/{business_id}/add-product", name:"add_product_route")]
    public function addProduct(Request $request, $business_id, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve the business entity using the business ID
            $business = $entityManager->getRepository(Business::class)->find($business_id);
            if (!$business) {
                throw $this->createNotFoundException('Business not found');
            }

            // Set the business on the product
            $product->setBusiness($business);
            
            // Persist the product entity
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_business', ['business_id' => $business_id]);
        }

        // Retrieve the business entity using the business ID
        $business = $entityManager->getRepository(Business::class)->find($business_id);
        if (!$business) {
            throw $this->createNotFoundException('Business not found');
        }

        return $this->render('product/add_product.html.twig', [
            'form' => $form->createView(),
            'business' => $business, // Pass the business variable to the template
        ]);
    }

    #[Route("/product/{id}", name:"product_show")]
    public function showProduct($id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route("/product/{id}/edit", name:"product_edit")]
    public function editProduct(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route("/product/{id}/delete", name:"product_delete")]
    public function deleteProduct($id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_business', ['business_id' => $product->getBusiness()->getId()]);
    }
}