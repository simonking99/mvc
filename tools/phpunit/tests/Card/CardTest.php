<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify properties.
     */
    public function testCreateObject()
    {
        $card = new Card("♠", "A");

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals("♠", $card->getSuit());
        $this->assertEquals("A", $card->getValue());
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $card = new Card("♦", "10");

        $this->assertEquals("10♦", (string)$card);
    }

    /**
     * Test setValue method.
     */
    public function testSetValue()
    {
        $card = new Card("♣", "5");
        $card->setValue("7");

        $this->assertEquals("7", $card->getValue());
    }
}
