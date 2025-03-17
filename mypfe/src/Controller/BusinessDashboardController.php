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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    //------------------
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

//----------------------------------------------------------------
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
        //----------------------------------
    }
    #[Route("/product/{id}/edit", name:"product_edit")]
    public function editProduct(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
      // Récupérer le produit
      $product = $entityManager->getRepository(Product::class)->find($id);
      if (!$product) {
          throw $this->createNotFoundException('Product not found');
      }

      // Créer le formulaire
      $form = $this->createForm(ProductType::class, $product);
      $form->handleRequest($request);

      // Traitement du formulaire
      if ($form->isSubmitted() && $form->isValid()) {
          // Récupérer le fichier image
          $photoFile = $form->get('photoFile')->getData();

          // Si un fichier est téléchargé, on le déplace et on met à jour le nom du fichier
          if ($photoFile) {
              $newFilename = uniqid() . '.' . $photoFile->guessExtension();
              $photoFile->move(
                  $this->getParameter('kernel.project_dir') . '/public/uploads/products',
                  $newFilename
              );
              $product->setImageproduct($newFilename);
          }

          // Sauvegarder les modifications
          $entityManager->flush();

          // Message flash de succès
          $this->addFlash('success', 'Product updated successfully!');

          // Redirection vers la page de détail du produit
          return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }      

      return $this->render('product/edit.html.twig', [
          'form' => $form->createView(),
          'product' => $product,
      ]);
    }
    //----------------------------------------------------------
    
    #[Route("/business/{business_id}/add-product", name:"add_product_route")]
    public function addProduct(Request $request, $business_id, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $business = $entityManager->getRepository(Business::class)->find($business_id);
            if (!$business) {
                throw $this->createNotFoundException('Business not found');
            }

            // Handle file upload manually
            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'), // Ensure this is set in services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                    return $this->redirectToRoute('add_product_route', ['business_id' => $business_id]);
                }

                $product->setImageProduct($newFilename); // Save filename in DB
                $product->setUpdatedAt(new \DateTime());
            }

            // Associate product with business
            $product->setBusiness($business);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product added successfully.');
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
