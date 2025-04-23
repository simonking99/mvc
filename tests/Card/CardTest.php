<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;


class CardTest extends TestCase
{   
    // Test to create a card and access its properties
    public function testCreateAndAccess()
    {
        $card = new Card("♠", "A");

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals("♠", $card->getSuit());
        $this->assertEquals("A", $card->getValue());
    }

    // Test to set and get the suit of a card
    public function testSetValue()
    {
        $card = new Card("♣", "2");
        $card->setValue("9");

        $this->assertEquals("9", $card->getValue());
    }
}
