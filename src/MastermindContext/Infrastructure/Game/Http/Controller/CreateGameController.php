<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Http\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateGameController
{
    public function __invoke(): Response
    {
        return new JsonResponse(['id' => Uuid::v4()->toRfc4122()], Response::HTTP_CREATED);
    }
}
