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

    #[Route('/proj/play', name: 'proj_play')]
    public function play(SessionInterface $session): Response
    {
        $blackjack = $session->get('blackjack');

        if (!$blackjack instanceof Blackjack) {
            return $this->redirectToRoute('proj_login');
        }

        $players = $blackjack->getPlayers();
        $games = $blackjack->getGames();
        $dealer = $blackjack->getDealer();
        $dealerHand = $dealer->getDealerHand()->getCards();

        $hands = [];
        $scores = [];
        $winners = [];
        $gameOver = [];

        $calculator = new ScoreCalculator();

        foreach ($games as $i => $game) {
            $hands[$i] = $game->getPlayerHand()->getCards();
            $scores[$i] = $calculator->calculate($game->getPlayerHand());
            $winners[$i] = $game->isGameOver() ? $game->getWinner() : '';
            $gameOver[$i] = $game->isGameOver();
        }

        $allDone = !in_array(false, $gameOver, true);
        $dealerScore = $allDone ? $calculator->calculate($dealer->getDealerHand()) : null;

        return $this->render('project/play.html.twig', [
            'players' => $players,
            'hands' => $hands,
            'scores' => $scores,
            'winners' => $winners,
            'gameOver' => $gameOver,
            'dealerHand' => $dealerHand,
            'dealerScore' => $dealerScore,
            'allDone' => $allDone
        ]);
    }

    #[Route('/proj/play/{player}/{action}', name: 'proj_play_action', methods: ['POST'])]
    public function playAction(SessionInterface $session, Request $request, int $player, string $action): Response
    {
        $blackjack = $session->get('blackjack');

        if (!$blackjack instanceof Blackjack) {
            return $this->redirectToRoute('proj_play');
        }

        $games = $blackjack->getGames();
        $players = $blackjack->getPlayers();

        if (!isset($games[$player], $players[$player])) {
            return $this->redirectToRoute('proj_play');
        }

        $game = $games[$player];

        if ($game->isGameOver()) {
            return $this->redirectToRoute('proj_play');
        }

        if ($action === 'bet') {
            $amount = intval($request->request->get('bet') ?? 0);
            $blackjack->placeBet($player, $amount);

            if ($blackjack->allBetsPlaced()) {
                $blackjack->startRound();
            }

        } elseif ($action === 'hit') {
            $game->dealCardToPlayer();
            if ($game->calculateScore($game->getPlayerHand()) > 21) {
                $game->checkWinner();
            }

        } elseif ($action === 'stand') {
            $game->markAsStood();
        }

        $allDone = true;
        foreach ($games as $g) {
            if (!$g->isGameOver()) {
                $allDone = false;
                break;
            }
        }

        if ($allDone) {
            $blackjack->finishRound();

            if (count($blackjack->getPlayers()) === 0) {
                $session->clear();
                return $this->redirectToRoute('proj_login');
            }
        }

        $session->set('blackjack', $blackjack);

        return $this->redirectToRoute('proj_play');
    }

    #[Route('/proj/new-round', name: 'proj_new_round')]
    public function newRound(SessionInterface $session): Response
    {
        $oldGame = $session->get('blackjack');
        if ($oldGame instanceof Blackjack) {
            $players = $oldGame->getPlayers();
            $session->set('blackjack', new Blackjack($players));
        }

        return $this->redirectToRoute('proj_play');
    }

    #[Route('/proj/reset', name: 'proj_reset')]
    public function reset(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('proj_login');
    }
}
