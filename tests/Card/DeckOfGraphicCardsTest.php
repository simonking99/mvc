<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfGraphicCards;

class DeckOfGraphicCardsTest extends TestCase
{
    // Construct object and verify that it is an instance of DeckOfGraphicCards.
    public function testCreateObject(): void
    {
        $obj = new DeckOfGraphicCards();
        $this->assertInstanceOf(DeckOfGraphicCards::class, $obj);
    }
}
