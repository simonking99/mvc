<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

class CardHandTest extends TestCase
{
    //Test to add and count cards
    public function testAddAndCountCards()
    {
        $hand = new CardHand();
        $this->assertEquals(0, $hand->getNumberOfCards());

        $card1 = new Card("♠", "A");
        $card2 = new Card("♦", "10");

        $hand->add($card1);
        $hand->add($card2);

        $this->assertEquals(2, $hand->getNumberOfCards());
    }
}
