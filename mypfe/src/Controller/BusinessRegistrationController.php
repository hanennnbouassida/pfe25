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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BusinessRegistrationController extends AbstractController
{
    #[Route('/register/business', name: 'app_register_business')]
    public function registerBusiness(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $business = new Business();
        $form = $this->createForm(BusinessRegistrationFormType::class, $business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle logo file upload and convert to Base64
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                try {
                    // Get the file content and encode it as Base64
                    $fileContent = file_get_contents($logoFile->getPathname());
                    $base64Logo = base64_encode($fileContent);

                    // Set the Base64-encoded logo in the entity
                    $business->setLogoBase64($base64Logo);
                } catch (FileException $e) {
                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                    return $this->redirectToRoute('app_register_business');
                }
            }

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
                return $this->redirectToRoute('app_login');
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register_business.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
