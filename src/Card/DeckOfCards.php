<?php
namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    private array $deck = [];

    public function __construct(bool $useGraphic = false)
    {
        $suits = ['♠', '♥', '♦', '♣'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = $useGraphic
                    ? new CardGraphic($suit, $value)
                    : new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function draw(int $count = 1): array
    {
        return array_splice($this->deck, 0, $count);
    }

    public function getCards(): array
    {
        return $this->deck;
    }

    public function count(): int
    {
        return count($this->deck);
    }

    public function reset(): void
    {
        $this->__construct();
    }
}
