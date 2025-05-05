<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game;
use App\Game\ScoreCalculator;
use App\Card\Card;

class GameTest extends TestCase
{
    public function testCreateObject(): void
    {
        $game = new Game();
        $this->assertInstanceOf(Game::class, $game);
    }

    public function testInitialHandsAreEmpty(): void
    {
        $game = new Game();
        $this->assertCount(0, $game->getPlayerHand()->getCards());
        $this->assertCount(0, $game->getDealerHand()->getCards());
    }

    public function testDealCardToPlayer(): void
    {
        $game = new Game();
        $game->dealCardToPlayer();
        $this->assertCount(1, $game->getPlayerHand()->getCards());
    }

    public function testDealCardToDealer(): void
    {
        $game = new Game();
        $game->dealCardToDealer();
        $this->assertCount(1, $game->getDealerHand()->getCards());
    }

    public function testPlayerAceHandledCorrectly(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "5"));
        $game->getPlayerHand()->add(new Card("♠", "A"));

        $calculator = new ScoreCalculator();
        $score = $calculator->calculate($game->getPlayerHand());

        $this->assertEquals(16, $score);
    }

    public function testDealerAceHandledCorrectly(): void
    {
        $game = new Game();
        $game->getDealerHand()->add(new Card("♠", "10"));
        $game->getDealerHand()->add(new Card("♦", "A"));

        $calculator = new ScoreCalculator();
        $score = $calculator->calculate($game->getDealerHand());

        $this->assertEquals(21, $score);
    }

    public function testDealerAceAsOne(): void
    {
        $game = new Game();
        $game->getDealerHand()->add(new Card("♠", "10"));
        $game->getDealerHand()->add(new Card("♦", "10"));
        $game->getDealerHand()->add(new Card("♣", "A"));

        $calculator = new ScoreCalculator();
        $score = $calculator->calculate($game->getDealerHand());

        $this->assertEquals(21, $score);
    }

    public function testCheckGameStatusPlayerBust(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "10"));
        $game->getPlayerHand()->add(new Card("♦", "10"));
        $game->getPlayerHand()->add(new Card("♣", "5"));
        $game->checkGameStatus();
        $this->assertTrue($game->isGameOver());
        $this->assertEquals("Dealer Wins", $game->getWinner());
    }

    public function testCheckGameStatusPlayer21(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "10"));
        $game->getPlayerHand()->add(new Card("♦", "A"));
        $game->checkGameStatus();
        $this->assertTrue($game->isGameOver());
        $this->assertEquals("Player Wins", $game->getWinner());
    }

    public function testCheckWinnerDealerBusts(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "5"));
        $game->getDealerHand()->add(new Card("♣", "10"));
        $game->getDealerHand()->add(new Card("♦", "10"));
        $game->getDealerHand()->add(new Card("♥", "5"));
        $game->checkWinner();
        $this->assertEquals("Player Wins", $game->getWinner());
        $this->assertTrue($game->isGameOver());
    }

    public function testDealerTurnEndsGame(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♣", "2"));
        $game->dealerTurn();
        $this->assertTrue($game->isGameOver());
        $this->assertNotEmpty($game->getWinner());
    }

    public function testCheckWinnerPlayerWins(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "10"));
        $game->getPlayerHand()->add(new Card("♦", "9"));

        $game->getDealerHand()->add(new Card("♠", "5"));
        $game->getDealerHand()->add(new Card("♦", "5"));

        $game->checkWinner();

        $this->assertEquals("Player Wins", $game->getWinner());
    }

    public function testCheckWinnerDealerWins(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "5"));
        $game->getPlayerHand()->add(new Card("♦", "6"));

        $game->getDealerHand()->add(new Card("♠", "10"));
        $game->getDealerHand()->add(new Card("♦", "10"));

        $game->checkWinner();

        $this->assertEquals("Dealer Wins", $game->getWinner());
    }

    public function testCheckWinnerDraw(): void
    {
        $game = new Game();
        $game->getPlayerHand()->add(new Card("♠", "10"));
        $game->getDealerHand()->add(new Card("♦", "10"));

        $game->checkWinner();

        $this->assertEquals("Draw", $game->getWinner());
    }
}
