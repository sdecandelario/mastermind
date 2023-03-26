<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;

final class GameBuilder
{
    private ?GameId $id;

    public function __construct(?GameId $id)
    {
        $this->id = $id ?? GameId::create();
    }

    public static function create(?GameId $id = null): GameBuilder
    {
        return new self($id);
    }

    public function withId(GameId $id): GameBuilder
    {
        $this->id = $id;

        return $this;
    }

    public function build()
    {
        return Game::create(
            $this->id
        );
    }
}
