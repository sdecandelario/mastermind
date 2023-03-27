<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Guess;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Game;

final class Guess
{
    private function __construct(
        private readonly GuessId $id,
        private readonly Game $game,
        private readonly ColorCode $colorCode,
        private int $blackPeg = 0
    ) {
    }

    public static function create(GuessId $guessId, Game $game, ColorCode $colorCode): Guess
    {
        return new self($guessId, $game, $colorCode);
    }

    public function id(): GuessId
    {
        return $this->id;
    }

    public function colorCode(): ColorCode
    {
        return $this->colorCode;
    }

    public function blackPeg(): int
    {
        return $this->blackPeg;
    }

    public function calculateBlackPegs(ColorCode $secretCode): void
    {
        $this->blackPeg = $this->colorCode->checkBlackPegs($secretCode);
    }
}
