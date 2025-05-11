<?php

namespace App\Controller;

use App\Game\Game;
use App\Game\ScoreCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

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
        $calculator = new ScoreCalculator();

        $playerScore = $calculator->calculate($game->getPlayerHand());
        $dealerScore = $calculator->calculate($game->getDealerHand());

        $winner = '';
        if ($playerScore > 21) {
            $winner = 'Dealer Wins';
        } elseif (21 == $playerScore) {
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

        if (!$game instanceof Game) {
            return $this->redirectToRoute('game_start');
        }

        $game->dealCardToPlayer();
        $calculator = new ScoreCalculator();

        $playerScore = $calculator->calculate($game->getPlayerHand());
        $dealerScore = $calculator->calculate($game->getDealerHand());

        if (21 == $playerScore) {
            return $this->redirectToRoute('game_stand');
        }

        $winner = $game->isGameOver() ? $game->getWinner() : '';

        $session->set('game', $game);

        return $this->render('game/start.html.twig', [
            'game' => $game,
            'playerHand' => $game->getPlayerHand()->getCards(),
            'dealerHand' => $game->getDealerHand()->getCards(),
            'playerScore' => $playerScore,
            'dealerScore' => $dealerScore,
            'winner' => $winner,
        ]);
    }

    #[Route('/game/stand', name: 'game_stand')]
    public function stand(SessionInterface $session): Response
    {
        $game = $session->get('game');

        if (!$game instanceof Game) {
            return $this->redirectToRoute('game_start');
        }

        $game->dealerTurn();
        $calculator = new ScoreCalculator();

        $session->set('game', $game);

        return $this->render('game/start.html.twig', [
            'game' => $game,
            'playerHand' => $game->getPlayerHand()->getCards(),
            'dealerHand' => $game->getDealerHand()->getCards(),
            'playerScore' => $calculator->calculate($game->getPlayerHand()),
            'dealerScore' => $calculator->calculate($game->getDealerHand()),
            'winner' => $game->getWinner(),
        ]);
    }

    #[Route('/game/reset', name: 'game_reset')]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('game');

        return $this->redirectToRoute('game_start');
    }
}
