<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Query;

use App\MastermindContext\Domain\Game\Exception\GameNotFoundException;
use App\MastermindContext\Domain\Game\Service\GameFinder;

final class GetGameQueryHandler
{
    public function __construct(private readonly GameFinder $gameFinder)
    {
    }

    /**
     * @throws GameNotFoundException
     */
    public function __invoke(GetGameQuery $query): GetGameQueryResult
    {
        $game = $this->gameFinder->findOrFail($query->gameId());

        return GetGameQueryResult::create($game);
    }
}
