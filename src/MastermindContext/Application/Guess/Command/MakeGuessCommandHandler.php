<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Guess\Command;

use App\MastermindContext\Domain\Game\Exception\GameNotFoundException;
use App\MastermindContext\Domain\Game\Service\GameFinder;

final class MakeGuessCommandHandler
{
    public function __construct(private readonly GameFinder $gameFinder)
    {
    }

    /**
     * @throws GameNotFoundException
     */
    public function __invoke(MakeGuessCommand $command): void
    {
        $game = $this->gameFinder->findOrFail($command->gameId());
    }
}
