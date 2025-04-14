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

        // Hantera ess för spelaren
        if ($card->getValue() === 'A') {
            $this->handlePlayerAce();
        }

        $this->checkGameStatus();
    }

    public function dealCardToDealer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->dealerHand->add($card);

        // Hantera ess för dealern
        if ($card->getValue() === 'A') {
            $this->handleDealerAce();
        }

        $this->checkGameStatus();
    }

    private function handlePlayerAce(): void
    {
        // Låt spelaren välja om esset ska vara 1 eller 11
        // Här kan du implementera en mekanism för att låta spelaren välja
        // För enkelhetens skull antar vi att spelaren alltid väljer 11 om det inte gör att de går över 21
        $playerScore = $this->calculateHandValue($this->playerHand);
        if ($playerScore + 10 <= 21) {
            $this->playerHand->adjustAceValue(11); // Anta att `adjustAceValue` är en metod i `CardHand`
        }
    }

    private function handleDealerAce(): void
    {
        // Anpassa essets värde baserat på dealerns nuvarande poäng
        $dealerScore = $this->calculateHandValue($this->dealerHand);
        if ($dealerScore + 10 <= 21) {
            $this->dealerHand->adjustAceValue(11); // Anta att `adjustAceValue` är en metod i `CardHand`
        } else {
            $this->dealerHand->adjustAceValue(1);
        }
    }

    public function calculateHandValue(CardHand $hand): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($hand->getCards() as $card) {
            if (in_array($card->getValue(), ['J', 'Q', 'K'])) {
                $value += 10;
            } elseif ($card->getValue() == 'A') {
                $value += 1;
                $aceCount++;
            } else {
                $value += (int) $card->getValue();
            }
        }

        // Hantera Aces (som kan vara både 1 eller 11)
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
