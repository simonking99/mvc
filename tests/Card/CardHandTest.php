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

    // Test to get all cards from the deck
    public function testGetCards()
    {
        $hand = new CardHand();
        $card = new Card("♣", "7");
        $hand->add($card);

        $cards = $hand->getCards();
        $this->assertIsArray($cards);
        $this->assertContainsOnlyInstancesOf(Card::class, $cards);
        $this->assertSame($card, $cards[0]);
    }

    // Test to convert the hand to a string
    public function testToString()
    {
        $hand = new CardHand();
        $hand->add(new Card("♥", "K"));
        $result = (string)$hand;
        $this->assertIsString($result);
        $this->assertStringContainsString("K♥", $result);
    }

    // Test to adjust the value of an Ace
    public function testAdjustAceValue()
    {
        $hand = new CardHand();
        $hand->add(new Card("♣", "3"));
        $hand->add(new Card("♠", "A"));
        $hand->adjustAceValue(11);

        $cards = $hand->getCards();
        $this->assertEquals("3", $cards[0]->getValue());
        $this->assertEquals("11", $cards[1]->getValue());
    }
}
