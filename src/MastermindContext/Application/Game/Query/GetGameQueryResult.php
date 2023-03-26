<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Query;

use App\MastermindContext\Domain\Game\Game;
use App\Shared\Domain\Query\QueryResultInterface;

final class GetGameQueryResult implements QueryResultInterface
{
    private function __construct(private readonly Game $game)
    {
    }

    public static function create(Game $game): GetGameQueryResult
    {
        return new self($game);
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->game->id(),
            'status' => $this->game->status()->value,
        ];
    }
}
