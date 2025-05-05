<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

class Game
{
    private bool $gameOver = false;
    private string $winner = '';

    private CardHand $playerHand;
    private CardHand $dealerHand;
    private DeckOfCards $deck;
    private ScoreCalculator $scoreCalculator;

    public function __construct()
    {
        $this->playerHand = new CardHand();
        $this->dealerHand = new CardHand();
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->scoreCalculator = new ScoreCalculator();
    }

    public function dealCardToPlayer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->playerHand->add($card);
        $this->checkGameStatus();
    }

    public function dealCardToDealer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->dealerHand->add($card);
        $this->checkGameStatus();
    }

    public function checkGameStatus(): void
    {
        $playerScore = $this->scoreCalculator->calculate($this->playerHand);

        if ($playerScore > 21) {
            $this->gameOver = true;
            $this->winner = "Dealer Wins";
        } elseif ($playerScore == 21) {
            $this->gameOver = true;
            $this->winner = "Player Wins";
        }
    }

    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    public function getDealerHand(): CardHand
    {
        return $this->dealerHand;
    }

    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    public function getWinner(): string
    {
        return $this->winner;
    }

    public function dealerTurn(): void
    {
        while ($this->scoreCalculator->calculate($this->dealerHand) < 17 && !$this->gameOver) {
            $this->dealCardToDealer();
        }

        $this->checkWinner();
    }

    public function checkWinner(): void
    {
        $playerScore = $this->scoreCalculator->calculate($this->playerHand);
        $dealerScore = $this->scoreCalculator->calculate($this->dealerHand);

        if ($dealerScore > 21) {
            $this->winner = "Player Wins";
        } elseif ($playerScore > $dealerScore) {
            $this->winner = "Player Wins";
        } elseif ($playerScore < $dealerScore) {
            $this->winner = "Dealer Wins";
        } else {
            $this->winner = "Draw";
        }

        $this->gameOver = true;
    }
}