<?php
namespace App\Controller;

use App\Entity\Business;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

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
}