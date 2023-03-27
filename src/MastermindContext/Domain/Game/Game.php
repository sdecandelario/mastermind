<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Guess\Guess;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Game
{
    private Collection $guesses;

    private function __construct(
        private readonly GameId $id,
        private GameStatus $status,
        private ColorCode $secretCode,
    ) {
        $this->guesses = new ArrayCollection();
    }

    public static function create(
        GameId $gameId,
        ?ColorCode $colorCode = null
    ): Game {
        return new self($gameId, GameStatus::Started, $colorCode ?? ColorCode::random());
    }

    public function addGuess(Guess $guess): void
    {
        $guess->calculatePegs($this->secretCode);

        $this->guesses->add($guess);

        if (1 === $this->guesses->count()) {
            $this->status = GameStatus::InProgress;
        }
    }

    public function id(): GameId
    {
        return $this->id;
    }

    public function status(): GameStatus
    {
        return $this->status;
    }

    public function secretCode(): ColorCode
    {
        return $this->secretCode;
    }

    public function isStarted(): bool
    {
        return GameStatus::Started === $this->status;
    }

    public function isInProgress(): bool
    {
        return GameStatus::InProgress === $this->status;
    }

    public function isWinner(): bool
    {
        return GameStatus::Won === $this->status;
    }

    public function guesses(): Collection
    {
        return $this->guesses;
    }
}
