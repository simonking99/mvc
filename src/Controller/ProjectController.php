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

    #[Route('/proj/about', name: 'projabout')]
    public function about(): Response
    {
        return $this->render('project/about.html.twig');
    }

    #[Route('/proj/login', name: 'proj_login')]
    public function login(SessionInterface $session, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $names = array_filter([
                $request->request->get('player1'),
                $request->request->get('player2'),
                $request->request->get('player3')
            ]);

            $players = array_map(fn($name) => new Player($name), $names);
            $game = new Blackjack($players);

            $session->set('blackjack', $game);
            return $this->redirectToRoute('proj_play');
        }

        return $this->render('project/login.html.twig');
    }
}
