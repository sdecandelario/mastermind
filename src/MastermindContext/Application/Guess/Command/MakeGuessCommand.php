<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Guess\Command;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Guess\GuessId;
use App\Shared\Domain\Command\CommandInterface;

final class MakeGuessCommand implements CommandInterface
{
    public function __construct(
        private readonly GuessId $guessId,
        private readonly GameId $gameId,
        private readonly string $colorCode
    ) {
    }

    public function guessId(): GuessId
    {
        return $this->guessId;
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function colorCode(): string
    {
        return $this->colorCode;
    }
}
