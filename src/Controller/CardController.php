<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class CardController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function card_deck(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
    
        if (!$deck) {
            $deck = new DeckOfCards(true);
            $session->set('deck', $deck);
        }
        $this->addFlash('message', 'The deck has been shown.');

        return $this->render('card/card_deck.html.twig', [
            'cards' => $deck->getCards()
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function card_shuffle(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck) {
            $deck = new DeckOfCards(true);
        }
        $deck->shuffle();
        $session->set('deck', $deck);

        $this->addFlash('message', 'The deck has been shuffled.');

        return $this->render('card/card_shuffle.html.twig', [
            'cards' => $deck->getCards()
        ]);
    }
}