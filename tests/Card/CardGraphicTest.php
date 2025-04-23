<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

class CardGraphicTest extends TestCase
{
    // Test to create a card graphic and access its properties
    public function testCreateCardGraphic(): void
    {
        $card = new CardGraphic("♥", "A");
        $this->assertInstanceOf(CardGraphic::class, $card);
    }

    // Test to get Unicode representation of the card
    public function testGetUnicode(): void
    {
        $card = new CardGraphic("♠", "K");
        $unicode = $card->getUnicode();
        $this->assertEquals("[K♠]", $unicode);
    }
    
    // Test to set string representation of a card graphic
    public function testToStringInherited(): void
    {
        $card = new CardGraphic("♦", "Q");
        $this->assertEquals("Q♦", (string)$card);
    }
}
