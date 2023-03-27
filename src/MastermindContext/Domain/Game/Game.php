<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Guess\Guess;
use Doctrine\Common\Collections\ArrayCollection;

final class Game
{
    private ArrayCollection $guesses;

    private function __construct(
        private readonly GameId $id,
        private GameStatus $status,
        private ColorCode $secretCode
    ) {
        $this->guesses = new ArrayCollection();
    }

    public static function create(GameId $gameId): Game
    {
        return new self($gameId, GameStatus::Started, ColorCode::random());
    }

    public function addGuess(Guess $guess): void
    {
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

    public function guesses(): ArrayCollection
    {
        return $this->guesses;
    }
}
