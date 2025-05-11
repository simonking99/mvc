<?php

namespace App\Game;

class Player
{
    private string $name;
    private int $bank;
    private int $bet;

    /**
     * Constructor for the Player class.
     *
     * @param string $name The name of the player.
     * @param int $initialBank The initial amount of money in the player's bank (default: 1000).
     */
    public function __construct(string $name, int $initialBank = 1000)
    {
        $this->name = $name;
        $this->bank = $initialBank;
        $this->bet = 0;
    }

    /**
     * Get the player's name.
     *
     * @return string The name of the player.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the player's current bank balance.
     *
     * @return int The amount of money in the player's bank.
     */
    public function getBank(): int
    {
        return $this->bank;
    }

    /**
     * Get the player's current bet.
     *
     * @return int The amount of the player's current bet.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Place a bet for the player.
     *
     * @param int $amount The amount to bet.
     * @return bool True if the bet was successfully placed, false otherwise.
     */
    public function placeBet(int $amount): bool
    {
        if ($amount > 0 && $amount <= $this->bank) {
            $this->bet = $amount;

            return true;
        }

        return false;
    }

    /**
     * Apply a win to the player's bank.
     *
     * @param bool $blackjack True if the win is a blackjack (1.5x payout), false otherwise.
     */
    public function applyWin(bool $blackjack = false): void
    {
        $this->bank += $blackjack ? intval($this->bet * 1.5) : $this->bet;
    }

    /**
     * Apply a loss to the player's bank.
     */
    public function applyLoss(): void
    {
        $this->bank -= $this->bet;
    }

    /**
     * Reset the player's bet to 0.
     */
    public function resetBet(): void
    {
        $this->bet = 0;
    }

    /**
     * Check if the player is bankrupt.
     *
     * @return bool True if the player's bank is 0 or less, false otherwise.
     */
    public function isBankrupt(): bool
    {
        return $this->bank <= 0;
    }
}