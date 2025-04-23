<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

class CardGraphicTest extends TestCase
{
    // Test to create a card graphic and access its properties
    public function testCreateCardGraphic()
    {
        $card = new CardGraphic("♥", "A");
        $this->assertInstanceOf(CardGraphic::class, $card);
    }

    // Test to set and get the suit of a card graphic
    public function testGetUnicode()
    {
        $card = new CardGraphic("♠", "K");
        $unicode = $card->getUnicode();

        $this->assertIsString($unicode);
        $this->assertEquals("[K♠]", $unicode);
    }
}
