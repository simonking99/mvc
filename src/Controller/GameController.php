<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_landing')]
    public function landing(): Response
    {
        return $this->render('game/landing.html.twig');
    }
}