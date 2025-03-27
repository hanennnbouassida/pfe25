<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Business;
use App\Entity\Client;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\Admin;
use App\Form\AdminType; // Ensure this class exists in the specified namespace
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AdminDashboardController extends AbstractController
{   
    //Dashboard Overview

    //category management
    #[Route("/dashboard/admin", name: "add_category_route")]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $businesses = $entityManager->getRepository(Business::class)->findAll();
        $clients = $entityManager->getRepository(Client::class)->findAll();
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard_admin');
        }
    
        return $this->render('dashboards/dashboard_admin.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            'businesses' => $businesses,
            'clients' => $clients,
        ]);
    }
    // ProductController.php or a dedicated CategoryController
#[Route('/manage/categories', name: 'manage_categories', methods: ['GET'])]
public function manageCategories(EntityManagerInterface $entityManager): Response
{        $categories = $entityManager->getRepository(Category::class)->findAll();

    return $this->render('AdminManagement/manageCategories.html.twig',[
        'categories' => $categories,
    ]);
}

    //business management
    #[Route("/business/approve/{id}", name:"business_approve")]
    public function approveBusiness($id, EntityManagerInterface $entityManager): Response
    {
        $businesses = $entityManager->getRepository(Business::class)->find($id);
        if ($businesses) {
            $businesses->setStatus('approved');
            $entityManager->flush();
        }

        $businesses = $entityManager->getRepository(Business::class)->findAll();

        return $this->redirectToRoute('dashboard_admin', [
            'businesses' => $businesses
        ]);
    }

    #[Route("/business/reject/{id}", name:"business_reject")]   
    public function rejectBusiness($id, EntityManagerInterface $entityManager): Response
    {
        $businesses = $entityManager->getRepository(Business::class)->find($id);
        if ($businesses) {
            $businesses->setStatus('rejected');
            $entityManager->flush();
        }

        $businesses = $entityManager->getRepository(Business::class)->findAll();

        return $this->redirectToRoute('dashboard_admin', [
            'businesses' => $businesses
        ]);
    }
    #[Route('/manage/businesses', name: 'manage_businesses', methods: ['GET'])]
    public function manageBusinesses(EntityManagerInterface $entityManager): Response
    {
        $businesses = $entityManager->getRepository(Business::class)->findAll();
    
        return $this->render('AdminManagement/manageBusiness.html.twig', [
            'businesses' => $businesses,
        ]);
    }
    
    //client management
    #[Route('/admin/client/block/{id}', name: 'block_client')]
    public function blockClient($id, EntityManagerInterface $entityManager): Response
    {
        $clients = $entityManager->getRepository(Client::class)->find($id);
        if ($clients) {
            $clients->setStatus('blocked');
            $entityManager->flush();
        }

        $clients = $entityManager->getRepository(Client::class)->findAll();

        return $this->redirectToRoute('dashboard_admin', [
            'clients' => $clients
        ]);
    }

    #[Route('/admin/client/unblock/{id}', name: 'unblock_client')]
    public function unblockClient($id, EntityManagerInterface $entityManager): Response
    {
        $clients = $entityManager->getRepository(Client::class)->find($id);
        if ($clients) {
            $clients->setStatus('active');
            $entityManager->flush();
        }

        $clients = $entityManager->getRepository(Client::class)->findAll();

        return $this->redirectToRoute('dashboard_admin', [
            'clients' => $clients
        ]);
    }

    #[Route('/admin/client/delete/{id}', name: 'delete_client')]
    public function deleteClient(Client $clients, EntityManagerInterface $em): Response
    {
        // Remove client from the database
        $em->remove($clients);
        $em->flush();

        $this->addFlash('success', 'Client has been deleted.');
        return $this->redirectToRoute('dashboard_admin');
    }
    //render cllient listing
    #[Route('/manage/clients', name: 'manage_clients', methods: ['GET'])]
public function manageClients(EntityManagerInterface $entityManager): Response
{        $clients = $entityManager->getRepository(Client::class)->findAll();

    return $this->render('AdminManagement/manageClients.html.twig',[
        'clients' => $clients,
    ]);
}
 //register new admin
    #[Route('/admin/register', name: 'register_admin')]
    public function registerAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setPassword(
                $passwordEncoder->hashPassword(
                    $admin,
                    $form->get('password')->getData()
                )
            );

            $admin->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'New admin registered successfully.');

            return $this->redirectToRoute('dashboard/admin');
        }

        return $this->render('AdminManagement/registerAdmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}