<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Query;

use App\MastermindContext\Domain\Game\GameId;
use App\Shared\Domain\Query\QueryInterface;

final class GetGameQuery implements QueryInterface
{
    public function __construct(private readonly GameId $gameId)
    {
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }
}
