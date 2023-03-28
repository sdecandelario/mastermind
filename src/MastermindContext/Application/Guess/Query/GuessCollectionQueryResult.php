<?php

namespace App\MastermindContext\Application\Guess\Query;

use App\MastermindContext\Domain\Guess\Guess;

final class GuessCollectionQueryResult
{
    private array $guesses;

    private function __construct(array $guesses)
    {
        $this->guesses = $guesses;
    }

    public static function create(Guess ...$guesses): GuessCollectionQueryResult
    {
        return new self($guesses);
    }

    public function toArray(): array
    {
        return array_map(function (Guess $guess) {
            return GuessQueryResult::create($guess)->toArray();
        }, $this->guesses);
    }
}
