<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Application\Game\Query\GetGameQuery;
use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use App\MastermindContext\Domain\Game\GameId;
use App\Shared\Domain\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetGameController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    /**
     * @throws InvalidGameIdException
     */
    public function __invoke(string $id): JsonResponse
    {
        $this->queryBus->ask(new GetGameQuery(GameId::createFromString($id)));

        return new JsonResponse();
    }
}
