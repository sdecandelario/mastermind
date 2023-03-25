<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

final class Game
{
    private GameId $id;

    public function __construct(GameId $id)
    {
        $this->id = $id;
    }

    public static function create(GameId $gameId): Game
    {
        return new self($gameId);
    }

    public function id(): GameId
    {
        return $this->id;
    }
}
