<?php
namespace App\Controller;

use App\Entity\Business;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BusinessRegistrationFormType;

class BusinessProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/profile', name: 'profile')]
public function profile(): Response
{
    /** @var Business|null $business */
    $business = $this->security->getUser();

    if (!$business) {
        throw $this->createAccessDeniedException('User not authenticated');
    }
    $products = $business->getProducts();

    return $this->render('business/profile.html.twig', [
        'business' => $business,
        'products' => $products,
    ]);
}

// edit business profile
#[Route('/business/{id}/editProfile', name: 'business_editProfile')]
public function edit(Request $request, Business $business, EntityManagerInterface $entityManager)
{
    $form = $this->createForm(BusinessRegistrationFormType::class, $business);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($business);
        $entityManager->flush();

        $this->addFlash('success', 'Business profile updated successfully!');
        return $this->redirectToRoute('business_profile', ['id' => $business->getId()]);
    }

    return $this->render('business/editprofile.html.twig', [
        'registrationForm' => $form->createView(),
        'isEditing' => true // Flag to know if we are editing
    ]);
}
}