<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Guess;

use App\MastermindContext\Domain\Game\Game;

final class Guess
{
    private function __construct(
        private readonly GuessId $id,
        private readonly Game $game
    ) {
    }

    public static function create(GuessId $guessId, Game $game): Guess
    {
        return new self($guessId, $game);
    }

    public function id(): GuessId
    {
        return $this->id;
    }
}
