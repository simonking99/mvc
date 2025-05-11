<?php

namespace App\Game;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class Game
{
    private bool $gameOver = false;
    private bool $hasStood = false;
    private string $winner = '';

    private CardHand $playerHand;
    private CardHand $dealerHand;
    private DeckOfCards $deck;
    private bool $isDealer = false;

    /** @var array<int, int> */
    private array $aceValues = [];

    public function __construct()
    {
        $this->playerHand = new CardHand();
        $this->dealerHand = new CardHand();
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
    }

    public function setAsDealer(): void
    {
        $this->isDealer = true;
    }

    public function startRound(): void
    {
        if (!$this->isDealer) {
            $this->dealCardToPlayer();
            $this->dealCardToPlayer();
        }
        $this->dealCardToDealer();
        $this->checkGameStatus();
    }

    public function dealCardToPlayer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->playerHand->add($card);
        $this->registerAceValue($card, $this->playerHand);
        $this->checkGameStatus();
    }

    public function dealCardToDealer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->dealerHand->add($card);
        $this->registerAceValue($card, $this->dealerHand);
    }

    private function registerAceValue(Card $card, CardHand $hand): void
    {
        if ('A' !== $card->getValue()) {
            return;
        }

        $score = 0;

        foreach ($hand->getCards() as $c) {
            if ($c === $card) {
                continue; // Skippa esset vi just lade till
            }

            $v = $c->getValue();
            if ('A' === $v) {
                $score += $this->aceValues[spl_object_id($c)] ?? 1;
            } elseif (in_array($v, ['J', 'Q', 'K'])) {
                $score += 10;
            } else {
                $score += (int) $v;
            }
        }

        $this->aceValues[spl_object_id($card)] = ($score + 11 <= 21) ? 11 : 1;
    }

    public function calculateScore(CardHand $hand): int
    {
        $score = 0;

        foreach ($hand->getCards() as $card) {
            $value = $card->getValue();
            if ('A' === $value) {
                $score += $this->aceValues[spl_object_id($card)] ?? 1;
            } elseif (in_array($value, ['J', 'Q', 'K'])) {
                $score += 10;
            } else {
                $score += (int) $value;
            }
        }

        return $score;
    }

    public function checkGameStatus(): void
    {
        $playerScore = $this->calculateScore($this->playerHand);

        if ($playerScore > 21) {
            $this->gameOver = true;
            $this->winner = 'Player Bust';
        } elseif ($this->hasStood) {
            $this->gameOver = true; // Markera spelet som klart för denna spelare
        } elseif (21 === $playerScore && 2 === $this->playerHand->getNumberOfCards()) {
            $this->gameOver = true;
            $this->winner = 'Player Wins';
        }
    }

    public function markAsStood(): void
    {
        $this->hasStood = true;
        $this->checkGameStatus();
    }

    public function dealerTurn(): void
    {
        while ($this->calculateScore($this->dealerHand) < 17) {
            $this->dealCardToDealer();
        }

        // Kontrollera vinnaren efter att dealern har avslutat sin tur
        $this->checkWinner();
    }

    public function checkWinner(): void
    {
        $playerScore = $this->calculateScore($this->playerHand);
        $dealerScore = $this->calculateScore($this->dealerHand);

        if ($playerScore > 21) {
            $this->winner = 'Dealer Wins'; // Spelaren bustar
        } elseif ($dealerScore > 21) {
            $this->winner = 'Player Wins'; // Dealern bustar
        } elseif ($playerScore > $dealerScore) {
            $this->winner = 'Player Wins';
        } elseif ($playerScore < $dealerScore) {
            $this->winner = 'Dealer Wins';
        } else {
            $this->winner = 'Draw';
        }

        $this->gameOver = true;
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

    public function setWinner(string $result): void
    {
        $this->winner = $result;
        $this->gameOver = true;
    }
}
