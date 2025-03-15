<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CategoryType;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route("/add-category", name:"add-category")]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('category/add_category_route.html.twig', [
            'categoryType' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('No category found for id ' . $id);
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a été modifiée avec succès.');
            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('category/edit_category.html.twig', [
            'categoryType' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: "categorie_delete")]
    public function delete( int $id, EntityManagerInterface $entityManager): Response
    {
        $categorie = $entityManager->getRepository(Category::class)->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('No category found for id ' . $id);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();
        
        return $this->redirect('dashboard_admin');
    }
}