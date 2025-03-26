<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BusinessRepository;


class BrandController extends AbstractController
{
    #[Route("/brands", name:"brands")]
    public function brandsList(BusinessRepository $businessRepository): Response
    {
        // Fetch all businesses from the database
        $businesses = $businessRepository->findAll();

        // Return the rendered template with the data
        return $this->render('pages/brands.html.twig', [
            'businesses' => $businesses,
        ]);
    }


    // src/Controller/BrandController.php

#[Route('/business/{id}', name: 'business_profile')]
public function viewBusinessProfile(BusinessRepository $businessRepository, $id): Response
{
    $business = $businessRepository->find($id);

    if (!$business) {
        throw $this->createNotFoundException('Business not found');
    }

    return $this->render('pages/business_profile.html.twig', [
        'business' => $business,
    ]);
}

}