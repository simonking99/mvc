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
    public function apiDeck(): JsonResponse
    {
        $deck = new DeckOfCards(true);
        $cards = $deck->getCards();
        $data = [];

        foreach ($cards as $card) {
            $data[] = $card->__toString();
        }
        
        return $this->json([
            'deck' => $data,
            'count' => count($data)
        ]);
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function apiShuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set('deck', $deck);
        $cards = array_map(fn($card) => (string) $card, $deck->getCards());

        return $this->json([
            'deck' => $cards
        ]);
        error_log("post /api/deck/shuffle anropades");
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["POST"])]
    public function apiDrawCard(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
        }
        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => array_map(fn($card) => (string) $card, $drawn),
            'remaining' => $deck->count()
        ]);
    }

}
