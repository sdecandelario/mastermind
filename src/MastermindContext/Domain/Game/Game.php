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

    public function id(): GameId
    {
        return $this->id;
    }
}
