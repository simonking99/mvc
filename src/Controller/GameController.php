<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Game\Game;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_landing')]
    public function landing(): Response
    {
        return $this->render('game/landing.html.twig');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route('/game/start', name: 'game_start')]
    public function start(SessionInterface $session): Response
    {
        $game = $session->get('game');

        if (!$game instanceof Game) {
            $game = new Game();
            $session->set('game', $game);
        }

        $game->dealCardToPlayer();

        $playerScore = $game->calculateHandValue($game->getPlayerHand());
        $dealerScore = $game->calculateHandValue($game->getDealerHand());

        $winner = '';
        if ($playerScore > 21) {
            $winner = 'Dealer Wins';
        } elseif ($playerScore == 21) {
            $winner = 'Player Wins';
        }

        return $this->render('game/start.html.twig', [
            'playerHand' => $game->getPlayerHand()->getCards(),
            'dealerHand' => $game->getDealerHand()->getCards(),
            'playerScore' => $playerScore,
            'dealerScore' => $dealerScore,
            'winner' => $winner,
            'game' => $game,
        ]);
    }

    #[Route('/game/deal', name: 'game_deal')]
    public function dealCard(SessionInterface $session): Response
    {
        $game = $session->get('game');
        if (!$game) {
            return $this->redirectToRoute('game_start');
        }

        $game->dealCardToPlayer();

        if ($game->calculateHandValue($game->getPlayerHand()) == 21) {
            return $this->redirectToRoute('game_stand');
        }

        $winner = '';
        if ($game->isGameOver()) {
            $winner = $game->getWinner();
        }

        $session->set('game', $game);

        return $this->render('game/start.html.twig', [
            'game' => $game,
            'playerHand' => $game->getPlayerHand()->getCards(),
            'dealerHand' => $game->getDealerHand()->getCards(),
            'playerScore' => $game->calculateHandValue($game->getPlayerHand()),
            'dealerScore' => $game->calculateHandValue($game->getDealerHand()),
            'winner' => $winner,
        ]);
    }
}
