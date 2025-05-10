<?php

namespace App\Game;

class Blackjack
{
    /** @var Game[] */
    private array $games = [];

    private Game $dealerGame;

    /** @var Player[] */
    private array $players = [];

    public function __construct(array $players)
    {
        foreach ($players as $player) {
            $this->players[] = $player;
            $this->games[] = new Game();
        }

        $this->dealerGame = new Game();
        $this->dealerGame->setAsDealer();
    }

    public function getDealer(): Game
    {
        return $this->dealerGame;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getGames(): array
    {
        return $this->games;
    }

    public function placeBet(int $playerIndex, int $amount): void
    {
        $player = $this->players[$playerIndex];
        if ($amount > 0 && $amount <= $player->getBank()) {
            $player->placeBet($amount);
        }
    }

    public function allBetsPlaced(): bool
    {
        foreach ($this->players as $player) {
            if ($player->getBet() <= 0) {
                return false;
            }
        }
        return true;
    }

    public function startRound(): void
    {
        foreach ($this->games as $game) {
            $game->startRound();
        }
        $this->dealerGame->startRound();
    }
}
