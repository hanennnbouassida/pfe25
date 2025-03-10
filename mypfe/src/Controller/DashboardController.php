<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    
      #[Route("/dashboard_business", name:"dashboard_business")]
     
    public function businessDashboard(): Response
    {
        return $this->render('dashboards/dashboard_business.html.twig');
    }


     #[Route("/dashboard_client", name:"dashboard_client")]
     
    public function clientDashboard(): Response
    {
        return $this->render('dashboards/dashboard_client.html.twig');
    }
}
