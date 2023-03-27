<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Guess\Command;

use App\MastermindContext\Domain\Game\Exception\GameNotFoundException;
use App\MastermindContext\Domain\Game\Service\AddGuess;
use App\MastermindContext\Domain\Game\Service\GameFinder;
use App\MastermindContext\Domain\Guess\Guess;

final class MakeGuessCommandHandler
{
    public function __construct(
        private readonly GameFinder $gameFinder,
        private readonly AddGuess $addGuess
    ) {
    }

    /**
     * @throws GameNotFoundException
     */
    public function __invoke(MakeGuessCommand $command): void
    {
        $game = $this->gameFinder->findOrFail($command->gameId());

        $this->addGuess->addGuess($game, Guess::create($command->guessId(), $game));
    }
}
