<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class ApiCardController extends AbstractController
{
    #[Route("/api", name: "api_landing")]
    public function apiLanding(): Response
    {
        return $this->render('api/index.html.twig');
    }

    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $cards = array_map(fn ($card) => (string) $card, $deck->getCards());

        return $this->json([
            'deck' => $cards,
            'count' => count($cards)
        ]);
    }


    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function apiShuffle(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = array_map(fn ($card) => (string) $card, $deck->getCards());

        error_log("post /api/deck/shuffle anropades");

        return $this->json([
            'deck' => $cards
        ]);
    }


    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["POST"])]
    public function apiDrawCard(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $deck->shuffle();
        }
        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => array_map(fn ($card) => (string) $card, $drawn),
            'remaining' => $deck->count()
        ]);
    }

    #[Route("/api/deck/draw/{number}", name: "api_deck_draw_number", requirements: ["number" => "\d+"], methods: ["POST"])]
    public function apiDrawNumber(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $deck->shuffle();
        }
        $number = min($number, $deck->count());

        $drawn = $deck->draw($number);
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => array_map(fn ($card) => (string) $card, $drawn),
            'requested' => $number,
            'remaining' => $deck->count()
        ]);
    }

    #[Route('/api/game', name: 'api_game')]
    public function apiGame(SessionInterface $session): JsonResponse
    {
        $game = $session->get('game');

        if (!$game) {
            return $this->json(['error' => 'No game in session'], 404);
        }

        return $this->json([
            'playerHand' => array_map(fn ($card) => $card->__toString(), $game->getPlayerHand()->getCards()),
            'dealerHand' => array_map(fn ($card) => $card->__toString(), $game->getDealerHand()->getCards()),
            'playerScore' => $game->calculateHandValue($game->getPlayerHand()),
            'dealerScore' => $game->calculateHandValue($game->getDealerHand()),
            'winner' => $game->getWinner()
        ]);
    }


}
