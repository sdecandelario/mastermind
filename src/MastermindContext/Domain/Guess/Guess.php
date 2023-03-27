<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Guess;

final class Guess
{
    private function __construct(
        private readonly GuessId $id
    ) {
    }

    public static function create(GuessId $guessId): Guess
    {
        return new self($guessId);
    }

    public function id(): GuessId
    {
        return $this->id;
    }
}
