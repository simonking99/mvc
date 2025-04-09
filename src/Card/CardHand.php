<?php
namespace App\Card;

class CardHand
{
    private array $cards = [];

    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getNumberOfCards(): int
    {
        return count($this->cards);
    }

    public function __toString(): string
    {
        return implode(' ', $this->cards);
    }
}
