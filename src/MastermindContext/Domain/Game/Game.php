<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

use App\MastermindContext\Domain\ColorCode\ColorCode;

final class Game
{
    public function __construct(
        private readonly GameId $id,
        private GameStatus $status,
        private ColorCode $secretCode
    )
    {
    }

    public static function create(GameId $gameId): Game
    {
        return new self($gameId, GameStatus::Started, ColorCode::random());
    }

    public function id(): GameId
    {
        return $this->id;
    }

    public function secretCode(): ColorCode
    {
        return $this->secretCode;
    }

    public function isStarted(): bool
    {
        return GameStatus::Started === $this->status;
    }
}
