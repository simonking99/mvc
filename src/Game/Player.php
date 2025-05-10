<?php

namespace App\Game;

class Player
{
    private string $name;
    private int $bank;
    private int $bet;

    public function __construct(string $name, int $initialBank = 1000)
    {
        $this->name = $name;
        $this->bank = $initialBank;
        $this->bet = 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBank(): int
    {
        return $this->bank;
    }

    public function getBet(): int
    {
        return $this->bet;
    }
}
