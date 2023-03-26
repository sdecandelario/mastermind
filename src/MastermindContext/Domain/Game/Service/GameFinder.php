<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game\Service;

use App\MastermindContext\Domain\Game\Exception\GameNotFoundException;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;

final class GameFinder
{
    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @throws GameNotFoundException
     */
    public function findOrFail(GameId $gameId)
    {
        $game = $this->gameRepository->findById($gameId);

        if (null === $game) {
            throw GameNotFoundException::withId($gameId);
        }

        return $game;
    }
}
