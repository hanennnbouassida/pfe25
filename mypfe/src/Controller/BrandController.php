<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class BrandController extends AbstractController
{
    
    #[Route("/brands", name:"brands")]

    public function index(): Response
    {
        return $this->render('brands.html.twig');
    }
}