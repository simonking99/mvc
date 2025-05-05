<?php

namespace App\Game;

use App\Card\CardHand;

class ScoreCalculator
{
    public function calculate(CardHand $hand): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($hand->getCards() as $card) {
            if (in_array($card->getValue(), ['J', 'Q', 'K'])) {
                $value += 10;
                continue;
            }

            if ($card->getValue() === 'A') {
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
}
