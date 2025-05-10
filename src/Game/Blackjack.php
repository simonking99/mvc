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
}
