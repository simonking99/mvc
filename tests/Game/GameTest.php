<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game;
use App\Card\Card;

class GameTest extends TestCase
{

    // Test to create a game object
    public function testCreateObject()
    {
        $game = new Game();
        $this->assertInstanceOf(Game::class, $game);
    }

    // Test initial hands are empty
    public function testInitialHandsAreEmpty()
    {
        $game = new Game();
        $this->assertCount(0, $game->getPlayerHand()->getCards());
        $this->assertCount(0, $game->getDealerHand()->getCards());
    }
}