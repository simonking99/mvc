<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MetricsController extends AbstractController
{
    #[Route('/metrics', name: 'metrics')]
    public function landing(): Response
    {
        return $this->render('metrics/landing.html.twig');
    }
}