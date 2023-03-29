<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Guess\Query;

use App\MastermindContext\Domain\Guess\Guess;

final class GuessQueryResult
{
    public function __construct(private readonly Guess $guess)
    {
    }

    public static function create(Guess $guess): GuessQueryResult
    {
        return new self(
            $guess
        );
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->guess->id(),
            'created' => $this->guess->created()->format('Y-m-d H:i:s'),
            'colorCode' => $this->guess->colorCode()->value(),
            'blackPeg' => $this->guess->blackPeg(),
            'whitePeg' => $this->guess->whitePeg(),
        ];
    }
}
