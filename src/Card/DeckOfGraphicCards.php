<?php

namespace App\Card;

class DeckOfGraphicCards extends DeckOfCards
{
    public function __construct()
    {
        $suits = ['♠', '♥', '♦', '♣'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = new CardGraphic($suit, $value);
            }
        }
    }
}
