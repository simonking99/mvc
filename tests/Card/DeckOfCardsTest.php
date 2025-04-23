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

    // Test draw cards
    public function testDrawCards()
    {
        $deck = new DeckOfCards();
        $drawn = $deck->draw(3);

        $this->assertCount(3, $drawn);
        $this->assertContainsOnlyInstancesOf(Card::class, $drawn);
        $this->assertEquals(49, $deck->count());
    }

    // Test get cards
    public function testGetCards()
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();

        $this->assertIsArray($cards);
        $this->assertCount(52, $cards);
        $this->assertContainsOnlyInstancesOf(Card::class, $cards);
    }
}
