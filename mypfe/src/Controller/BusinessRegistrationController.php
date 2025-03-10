<?php

namespace App\Controller;

use App\Entity\Business;
use App\Form\BusinessRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BusinessRegistrationController extends AbstractController
{
    #[Route('/register/business', name: 'app_register_business')]
    public function registerBusiness(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $business = new Business(); // Assuming you have a Business entity
    $form = $this->createForm(BusinessRegistrationFormType::class, $business);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hash the password
        $business->setPassword(
            $passwordHasher->hashPassword(
                $business,
                $form->get('plainPassword')->getData()
            )
        );

        // Assign ROLE_BUSINESS to the new user
        $business->setRoles(['ROLE_BUSINESS']);

        // Persist and save to the database
        $entityManager->persist($business);
        $entityManager->flush();

        // Check role and redirect accordingly
        if (in_array('ROLE_BUSINESS', $business->getRoles())) {
            return $this->redirectToRoute('dashboard_business');
        }

        return $this->redirectToRoute('home');
    }

    return $this->render('registration/register_business.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

}
