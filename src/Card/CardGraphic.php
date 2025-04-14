<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function getUnicode(): string
    {
        $unicodeMap = [
            '♠' => 'Spades',
            '♥' => 'Hearts',
            '♦' => 'Diamonds',
            '♣' => 'Clubs',
        ];

        print_r($unicodeMap);
        return "[{$this->value}{$this->suit}]";
    }
}
