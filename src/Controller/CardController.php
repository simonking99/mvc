<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card', name: 'card')]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function cardDeck(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $this->addFlash('message', 'The deck has been shown.');

        return $this->render('card/card_deck.html.twig', [
            'cards' => $deck->getCards(),
        ]);
    }

    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $deck->shuffle();
        $session->set('deck', $deck);

        $this->addFlash('message', 'The deck has been shuffled.');

        return $this->render('card/card_shuffle.html.twig', [
            'cards' => $deck->getCards(),
        ]);
    }

    #[Route('/card/deck/draw', name: 'card_draw')]
    public function cardDraw(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        $this->addFlash('message', 'Card has been drawn.');

        return $this->render('card/card_draw.html.twig', [
            'drawn' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/card/deck/draw/{number}', name: 'card_draw_number', requirements: ['number' => "\d+"])]
    public function cardDrawNumber(SessionInterface $session, int $number): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $drawn = $deck->draw($number);
        $session->set('deck', $deck);

        $this->addFlash('message', "Cards drawn from the deck: $number.");

        return $this->render('card/card_draw_multiple.html.twig', [
            'drawn' => $drawn,
            'remaining' => $deck->count(),
            'amount' => $number,
        ]);
    }
}
