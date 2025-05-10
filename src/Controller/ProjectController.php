<?php

namespace App\Controller;

use App\Game\Blackjack;
use App\Game\Player;
use App\Game\ScoreCalculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ProjectController extends AbstractController
{
    #[Route('/proj', name: 'proj')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig');
    }
}
