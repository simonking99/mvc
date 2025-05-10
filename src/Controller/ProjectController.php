<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/proj', name: 'proj')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig');
    }

    #[Route('/proj/about', name: 'projabout')]
    public function about(): Response
    {
        return $this->render('project/about.html.twig');
    }
}
