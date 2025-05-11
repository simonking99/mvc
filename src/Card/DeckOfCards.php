<?php

namespace App\Card;

/**
 * @Card
 */
class DeckOfCards
{
    /** @var array<int, Card> */
    protected array $deck = [];

    public function __construct()
    {
        $suits = ['♠', '♥', '♦', '♣'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    /**
     * @return array<int, Card>
     */
    public function draw(int $count = 1): array
    {
        return array_splice($this->deck, 0, $count);
    }

    /**
     * @return array<int, Card>
     */
    public function getCards(): array
    {
        return $this->deck;
    }

    public function count(): int
    {
        return count($this->deck);
    }
}
