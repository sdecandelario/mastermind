<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Query;

use App\MastermindContext\Domain\Game\Service\GameFinder;

final class GetGameQueryHandler
{
    public function __construct(private readonly GameFinder $gameFinder)
    {
    }

    public function __invoke(GetGameQuery $query): void
    {
    }
}
