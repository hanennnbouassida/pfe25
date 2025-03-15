<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientDashboardController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route("/dashboard/client", name:"dashboard_client")]
    public function clientDashboard(): Response
    {
        return $this->render('dashboards/dashboard_client.html.twig');
    }
}