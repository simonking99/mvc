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
     * Constructor for the Blackjack game.
     *
     * @param Player[] $players An array of players participating in the game.
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

    /**
     * Get the dealer's game instance.
     *
     * @return Game The dealer's game.
     */
    public function getDealer(): Game
    {
        return $this->dealerGame;
    }

    /**
     * Get all players in the game.
     *
     * @return Player[] An array of players.
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * Get all game instances for the players.
     *
     * @return Game[] An array of games for each player.
     */
    public function getGames(): array
    {
        return $this->games;
    }

    /**
     * Place a bet for a specific player.
     *
     * @param int $playerIndex The index of the player placing the bet.
     * @param int $amount The amount of the bet.
     */
    public function placeBet(int $playerIndex, int $amount): void
    {
        $player = $this->players[$playerIndex];
        if ($amount > 0 && $amount <= $player->getBank()) {
            $player->placeBet($amount);
        }
    }

    /**
     * Check if all players have placed their bets.
     *
     * @return bool True if all players have placed bets, false otherwise.
     */
    public function allBetsPlaced(): bool
    {
        foreach ($this->players as $player) {
            if ($player->getBet() <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Start a new round for all players and the dealer.
     */
    public function startRound(): void
    {
        foreach ($this->games as $game) {
            $game->startRound();
        }
        $this->dealerGame->startRound();
    }

    /**
     * Finish the current round, determine winners, and update player states.
     */
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

    /**
     * Get all active players who are not bankrupt.
     *
     * @return Player[] An array of active players.
     */
    public function getActivePlayers(): array
    {
        return array_filter($this->players, fn ($p) => !$p->isBankrupt());
    }
}