<?php

namespace App\Card;

/**
 * A card with a suit and value.
 */
class Card
{
    protected string $suit;  // The suit of the card.
    protected string $value; // The value of the card.

    /**
     * Create a card with a suit and value.
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Get the suit.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get the value.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set a new value.
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * Get the card as a string.
     */
    public function __toString(): string
    {
        return "{$this->value}{$this->suit}";
    }

    public function getAsString(): string
    {
        return $this->value.$this->suit;
    }
}
