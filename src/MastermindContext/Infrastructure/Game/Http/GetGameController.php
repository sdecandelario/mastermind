<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Http;

use App\Shared\Domain\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetGameController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        return new JsonResponse();
    }
}
