<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Guess\Query;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Guess\Guess;
use App\MastermindContext\Domain\Guess\GuessId;

final class GuessQueryResult
{
    public function __construct(
        private readonly GuessId $id,
        private readonly ColorCode $colorCode,
        private readonly int $blackPeg,
        private readonly int $whitePeg,
    ) {
    }

    public static function create(Guess $guess): GuessQueryResult
    {
        return new self(
            $guess->id(),
            $guess->colorCode(),
            $guess->blackPeg(),
            $guess->whitePeg()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'colorCode' => $this->colorCode->value(),
            'blackPeg' => $this->blackPeg,
            'whitePeg' => $this->whitePeg,
        ];
    }
}
