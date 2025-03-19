<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientRegistrationController extends AbstractController
{
    #[Route('/register/client', name: 'app_register_client')]
    public function registerClient(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $client = new Client(); // Assuming you have a Client entity
        $form = $this->createForm(ClientRegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $client->setPassword(
                $passwordHasher->hashPassword(
                    $client,
                    $form->get('plainPassword')->getData()
                )
            );

            // Assign ROLE_CLIENT to the new user
            $client->setRoles(['ROLE_CLIENT']);

            // Read the num_tel property
            $num_tel= $client->getNumTel();

            // Persist and save to the database
            $entityManager->persist($client);
            $entityManager->flush();

            // Check role and redirect accordingly
            if (in_array('ROLE_CLIENT', $client->getRoles())) {
                return $this->redirectToRoute('app_login');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_client.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
