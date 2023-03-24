<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Http\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateGameController
{
    public function __invoke(): Response
    {
        return new JsonResponse(['id' => 'fakeId'], Response::HTTP_CREATED);
    }
}
