<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;

final class GameBuilder
{
    private ?GameId $id;

    private ColorCode $colorCode;

    public function __construct(?GameId $id)
    {
        $this->id = $id ?? GameId::create();
        $this->colorCode = ColorCode::random();
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

    public function withColorCode(ColorCode $colorCode): GameBuilder
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    public function build()
    {
        return Game::create(
            $this->id,
            $this->colorCode
        );
    }
}
