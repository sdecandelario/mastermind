<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

final class Game
{
    public function __construct(
        private readonly GameId $id,
        private GameStatus $status
    )
    {
    }

    public static function create(GameId $gameId): Game
    {
        return new self($gameId, GameStatus::Started);
    }

    public function id(): GameId
    {
        return $this->id;
    }

    public function isStarted(): bool
    {
        return GameStatus::Started === $this->status;
    }
}
