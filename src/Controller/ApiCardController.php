<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;

class ApiCardController extends AbstractController
{
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
}
