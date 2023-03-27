<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Guess\Guess;

final class GameBuilder
{
    private ?GameId $id;

    private ColorCode $colorCode;

    private array $guesses;

    private function __construct()
    {
        $this->id = GameId::create();
        $this->colorCode = ColorCode::random();
        $this->guesses = [];
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

    public function build(): Game
    {
        $game = Game::create(
            $this->id,
            $this->colorCode
        );

        foreach ($this->guesses as $guess) {
            $game->addGuess($guess);
        }

        return $game;
    }

    public function withGuess(Guess $guess)
    {
        $this->guesses[] = $guess;

        return $this;
    }
}
