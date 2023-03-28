<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameStatus;

final class GameBuilder
{
    private ?GameId $id;

    private ColorCode $colorCode;

    private GameStatus $status;

    private function __construct()
    {
        $this->id = GameId::create();
        $this->colorCode = ColorCode::random();
        $this->status = GameStatus::Started;
    }

    public static function create(): GameBuilder
    {
        return new self();
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

    public function withStatus(GameStatus $status): GameBuilder
    {
        $this->status = $status;

        return $this;
    }

    public function build(): Game
    {
        return Game::create(
            $this->id,
            $this->colorCode,
            $this->status
        );
    }
}
