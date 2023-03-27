<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Guess\Guess;
use App\MastermindContext\Domain\Guess\GuessId;

final class GuessBuilder
{
    private ?GuessId $id;

    private Game $game;

    private ColorCode $colorCode;

    public function __construct(Game $game)
    {
        $this->id = GuessId::create();
        $this->game = $game;
        $this->colorCode = ColorCode::random();
    }

    public static function create(Game $game): GuessBuilder
    {
        return new self($game);
    }

    public function withColorCode(ColorCode $colorCode): GuessBuilder
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    public function copy(): GuessBuilder
    {
        $copy = clone $this;
        $copy->id = GuessId::create();

        return clone $copy;
    }

    public function build(): Guess
    {
        return Guess::create(
            $this->id,
            $this->game,
            $this->colorCode
        );
    }
}
