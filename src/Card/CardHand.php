<?php

namespace App\Card;

/**
 * @property Card[] $cards
 */
class CardHand
{
    /** @var Card[] */
    private array $cards = [];

    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * @return Card[]
     */
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

    public function adjustAceValue(int $value): void
    {
        foreach ($this->cards as $card) {
            if ('A' === $card->getValue()) {
                $card->setValue((string) $value);
                break;
            }
        }
    }
}
