<?php

namespace App\Game;

class Blackjack
{
    /** @var Game[] */
    private array $games = [];

    private Game $dealerGame;

    /** @var Player[] */
    private array $players = [];

    /**
     * @param Player[] $players
     */
    public function __construct(array $players)
    {
        /** @var Player[] $players */
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

    /** @return Player[] */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /** @return Game[] */
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

    public function finishRound(): void
    {
        $this->dealerGame->dealerTurn();
        $dealerHand = $this->dealerGame->getDealerHand();
        $calculator = new ScoreCalculator();

        foreach ($this->games as $index => $game) {
            $player = $this->players[$index];
            $playerScore = $calculator->calculate($game->getPlayerHand());
            $dealerScore = $calculator->calculate($dealerHand);
            $blackjack = (21 === $playerScore && 2 === $game->getPlayerHand()->getNumberOfCards());

            if ($playerScore > 21) {
                $game->setWinner('Dealer Wins');
                $player->applyLoss();
            } elseif ($dealerScore > 21) {
                $game->setWinner('Player Wins');
                $player->applyWin($blackjack);
            } elseif ($playerScore > $dealerScore) {
                $game->setWinner('Player Wins');
                $player->applyWin($blackjack);
            } elseif ($playerScore < $dealerScore) {
                $game->setWinner('Dealer Wins');
                $player->applyLoss();
            } else {
                $game->setWinner('Draw');
            }

            $player->resetBet();
        }

        foreach ($this->players as $i => $player) {
            if ($player->isBankrupt()) {
                unset($this->players[$i]);
                unset($this->games[$i]);
            }
        }

        $this->players = array_values($this->players);
        $this->games = array_values($this->games);
    }

    /** @return Player[] */
    public function getActivePlayers(): array
    {
        return array_filter($this->players, fn ($p) => !$p->isBankrupt());
    }
}
