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
            'deck' => $cards,
            'count' => count($cards),
            'status' => 'shuffled'
        ]);
    }
}
