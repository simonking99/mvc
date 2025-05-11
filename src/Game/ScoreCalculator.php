<?php

namespace App\Game;

use App\Card\CardHand;

class ScoreCalculator
{
    /**
     * Calculate the total score of a given hand.
     *
     * This method calculates the score of a hand in Blackjack, taking into account
     * the special rules for aces, which can count as either 1 or 11 depending on
     * the total score.
     *
     * @param CardHand $hand The hand of cards to calculate the score for.
     * @return int The total score of the hand.
     */
    public function calculate(CardHand $hand): int
    {
        $value = 0;
        $aceCount = 0;

        // Iterate through the cards in the hand and calculate the score
        foreach ($hand->getCards() as $card) {
            if (in_array($card->getValue(), ['J', 'Q', 'K'])) {
                $value += 10; // Face cards are worth 10 points
                continue;
            }

            if ('A' === $card->getValue()) {
                ++$value; // Add 1 for an ace initially
                ++$aceCount; // Count the number of aces
                continue;
            }

            $value += (int) $card->getValue(); // Add the numeric value of the card
        }

        // Adjust the value of aces if possible to count one as 11
        while ($aceCount > 0 && $value + 10 <= 21) {
            $value += 10; // Upgrade an ace from 1 to 11
            --$aceCount;
        }

        return $value;
    }
}