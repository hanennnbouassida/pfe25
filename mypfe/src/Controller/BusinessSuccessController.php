<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusinessSuccessController extends AbstractController
{
    #[Route('/success', name: 'app_business_success')]
    public function success(): Response
    {
        return $this->render('business/success.html.twig');
    }
}
