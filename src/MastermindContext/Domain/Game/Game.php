<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Exception\FinishedGameException;
use App\MastermindContext\Domain\Guess\Guess;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Game
{
    private Collection $guesses;

    private function __construct(
        private readonly GameId $id,
        private GameStatus $status,
        private readonly ColorCode $secretCode,
    ) {
        $this->guesses = new ArrayCollection();
    }

    public static function create(
        GameId $gameId,
        ?ColorCode $colorCode = null,
        ?GameStatus $status = null
    ): Game {
        return new self($gameId, $status ?? GameStatus::Started, $colorCode ?? ColorCode::random());
    }

    public function addGuess(Guess $guess): void
    {
        $guess->calculatePegs($this->secretCode);

        $this->guesses->add($guess);

        if ($guess->allBlackPegs()) {
            $this->status = GameStatus::Won;
        } elseif (1 === $this->guesses->count()) {
            $this->status = GameStatus::InProgress;
        } elseif (10 === $this->guesses->count()) {
            $this->status = GameStatus::Lost;
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

    public function isLost(): bool
    {
        return GameStatus::Lost === $this->status;
    }

    /**
     * @throws FinishedGameException
     */
    public function assertIsFinished(): void
    {
        if ($this->isWinner() || $this->isLost()) {
            throw FinishedGameException::createWithGameId($this->id);
        }
    }

    public function guesses(): Collection
    {
        return $this->guesses;
    }
}
