<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Blackjack;
use App\Game\Player;
use App\Card\Card;
use App\Game\Game;


class BlackjackTest extends TestCase
{
    public function testConstructorInitializesGamesAndDealer(): void
    {
        $players = [new Player("Simon"), new Player("David")];
        $blackjack = new Blackjack($players);
        $dealer = $blackjack->getDealer();
        
        $this->assertInstanceOf(Game::class, $dealer);
    }

    public function testPlaceBetWithinLimit(): void
    {
        $players = [new Player("Simon")];
        $game = new Blackjack($players);
        $game->placeBet(0, 500);

        $this->assertEquals(500, $game->getPlayers()[0]->getBet());
    }

    public function testAllBetsPlacedReturnsFalseInitially(): void
    {
        $players = [new Player("Simon"), new Player("David")];
        $game = new Blackjack($players);

        $this->assertFalse($game->allBetsPlaced());
    }

    public function testAllBetsPlacedReturnsTrueAfterBets(): void
    {
        $players = [new Player("Simon"), new Player("David")];
        $game = new Blackjack($players);
        $game->placeBet(0, 100);
        $game->placeBet(1, 200);

        $this->assertTrue($game->allBetsPlaced());
    }

    public function testStartRoundDealsCards(): void
    {
        $players = [new Player("Simon")];
        $game = new Blackjack($players);

        $game->placeBet(0, 100);
        $game->startRound();

        $this->assertGreaterThan(0, count($game->getGames()[0]->getPlayerHand()->getCards()));
        $this->assertGreaterThan(0, count($game->getDealer()->getDealerHand()->getCards()));
    }

    public function testFinishRoundUpdatesState(): void
    {
        $players = [new Player("Simon")];
        $game = new Blackjack($players);

        $game->placeBet(0, 100);
        $game->startRound();
        $game->finishRound();

        $this->assertTrue($game->getGames()[0]->isGameOver());
    }

    public function testGetActivePlayersExcludesBankrupt(): void
    {
        $players = [new Player("B", 0), new Player("R", 1000)];
        $game = new Blackjack($players);

        $active = $game->getActivePlayers();
        $this->assertCount(1, $active);
        $this->assertEquals("R", $active[array_key_first($active)]->getName());
    }

    public function testFinishRoundDealerWins(): void
    {
        $player = new Player("Simon");
        $game = new Blackjack([$player]);
    
        $game->placeBet(0, 100);
    
        $game->getGames()[0]->getPlayerHand()->add(new Card("♠", "5"));
        $game->getDealer()->getDealerHand()->add(new Card("♣", "10"));
        $game->getDealer()->getDealerHand()->add(new Card("♦", "9"));
    
        $game->finishRound();
    
        $this->assertEquals("Dealer Wins", $game->getGames()[0]->getWinner());
    }

    public function testFinishRoundPlayerBusts(): void
    {
        $player = new Player("Simon");
        $game = new Blackjack([$player]);

        $game->placeBet(0, 100);
        $game->getGames()[0]->getPlayerHand()->add(new Card("♠", "10"));
        $game->getGames()[0]->getPlayerHand()->add(new Card("♦", "10"));
        $game->getGames()[0]->getPlayerHand()->add(new Card("♣", "5"));

        $game->finishRound();

        $this->assertEquals("Dealer Wins", $game->getGames()[0]->getWinner());
    } 

    public function testFinishRoundDraw(): void
    {
        $player = new Player("Simon");
        $game = new Blackjack([$player]);
    
        $game->placeBet(0, 100);
    
        $game->getGames()[0]->getPlayerHand()->add(new Card("♠", "10"));
        $game->getGames()[0]->getPlayerHand()->add(new Card("♦", "10"));
    
        $dealerHand = $game->getDealer()->getDealerHand();
        $dealerHand->add(new Card("♣", "10"));
        $dealerHand->add(new Card("♥", "10"));
    
        $game->finishRound();
    
        $this->assertEquals("Draw", $game->getGames()[0]->getWinner());
    }
    
    

    public function testFinishRoundPlayerWins(): void
    {
        $player = new Player("Simon");
        $game = new Blackjack([$player]);
    
        $game->placeBet(0, 100);
    
        $game->getGames()[0]->getPlayerHand()->add(new Card("♠", "10"));
        $game->getGames()[0]->getPlayerHand()->add(new Card("♦", "10"));
    
        $dealerHand = $game->getDealer()->getDealerHand();
        $dealerHand->add(new Card("♣", "10"));
        $dealerHand->add(new Card("♥", "7"));
    
        $game->finishRound();
    
        $this->assertEquals("Player Wins", $game->getGames()[0]->getWinner());
    }
    
    

    public function testFinishRoundRemovesBankruptPlayer(): void
    {
    $player = new Player("Simon", 100);
    $game = new Blackjack([$player]);

    $game->placeBet(0, 100);
    $game->getGames()[0]->getPlayerHand()->add(new Card("♠", "10"));
    $game->getGames()[0]->getPlayerHand()->add(new Card("♦", "10"));
    $game->getGames()[0]->getPlayerHand()->add(new Card("♣", "5"));
    $game->finishRound();

    $this->assertCount(0, $game->getPlayers());
    $this->assertCount(0, $game->getGames());
    }
}
