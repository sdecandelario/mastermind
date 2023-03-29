<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Query;

use App\MastermindContext\Application\Guess\Query\GuessCollectionQueryResult;
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
        $response = [
            'id' => (string) $this->game->id(),
            'created' => $this->game->created()->format('Y-m-d H:i:s'),
            'status' => $this->game->status()->value,
            'secretCode' => $this->game->secretCode()->value(),
            'guesses' => GuessCollectionQueryResult::create(...$this->game->guesses())->toArray(),
        ];

        if (false === $this->game->isWinner() && false === $this->game->isLost()) {
            unset($response['secretCode']);
        }

        return $response;
    }
}
