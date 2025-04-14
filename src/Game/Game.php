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
        $this->checkGameStatus();
    }

    public function dealCardToDealer(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->dealerHand->add($card);
        $this->checkGameStatus();
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

        while ($aceCount > 0 && $value <= 1) {
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
}
