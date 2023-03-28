<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game\Service;

use App\MastermindContext\Domain\Game\Exception\FinishedGameException;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\MastermindContext\Domain\Guess\Guess;

final class AddGuess
{
    public function __construct(private readonly GameRepositoryInterface $gameRepository)
    {
    }

    /**
     * @throws FinishedGameException
     */
    public function addGuess(Game $game, Guess $guess): void
    {
        $game->assertIsFinished();

        $game->addGuess($guess);

        $this->gameRepository->save($game);
    }
}
