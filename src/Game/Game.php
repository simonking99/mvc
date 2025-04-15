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

    public function __construct()
    {
        $this->playerHand = new CardHand();
        $this->dealerHand = new CardHand();
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
    }

    public function dealCardToPlayer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->playerHand->add($card);

        if ($card->getValue() === 'A') {
            $this->handlePlayerAce();
        }

        $this->checkGameStatus();
    }

    public function dealCardToDealer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->dealerHand->add($card);

        if ($card->getValue() === 'A') {
            $this->handleDealerAce();
        }

        $this->checkGameStatus();
    }

    private function handlePlayerAce(): void
    {
        $playerScore = $this->calculateHandValue($this->playerHand);
        if ($playerScore + 10 <= 21) {
            $this->playerHand->adjustAceValue(11);
        }
    }

    private function handleDealerAce(): void
    {
        $dealerScore = $this->calculateHandValue($this->dealerHand);
        if ($dealerScore + 10 <= 21) {
            $this->dealerHand->adjustAceValue(11);
            return;
        }

        $this->dealerHand->adjustAceValue(1);
    }


    public function calculateHandValue(CardHand $hand): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($hand->getCards() as $card) {
            if (in_array($card->getValue(), ['J', 'Q', 'K'])) {
                $value += 10;
                continue;
            }

            if ($card->getValue() == 'A') {
                $value += 1;
                $aceCount++;
                continue;
            }

            $value += (int) $card->getValue();
        }

        while ($aceCount > 0 && $value + 10 <= 21) {
            $value += 10;
            $aceCount--;
        }

        return $value;
    }

    public function checkGameStatus(): void
    {
        $playerScore = $this->calculateHandValue($this->playerHand);

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
        while ($this->calculateHandValue($this->dealerHand) < 17 && !$this->gameOver) {
            $this->dealCardToDealer();
        }

        $this->checkWinner();
    }

    public function checkWinner(): void
    {
        $playerScore = $this->calculateHandValue($this->playerHand);
        $dealerScore = $this->calculateHandValue($this->dealerHand);

        if ($dealerScore > 21) {
            $this->winner = "Player Wins";
            $this->gameOver = true;
            return;
        }

        if ($playerScore > $dealerScore) {
            $this->winner = "Player Wins";
            $this->gameOver = true;
            return;
        }

        if ($playerScore < $dealerScore) {
            $this->winner = "Dealer Wins";
            $this->gameOver = true;
            return;
        }

        $this->winner = "Draw";
        $this->gameOver = true;
    }
}
