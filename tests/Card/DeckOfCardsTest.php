<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCards;
use App\Card\Card;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    // Test to create a deck of cards with 52 cards
    public function testInitialCardCount()
    {
        $deck = new DeckOfCards();
        $this->assertEquals(52, $deck->count());
    }
}
