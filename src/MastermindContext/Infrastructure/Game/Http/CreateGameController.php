<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Application\Game\Command\CreateGameCommand;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Infrastructure\Game\Validator\Request\CreateGameRequestValidator;
use App\Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateGameController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly CreateGameRequestValidator $createGameRequestValidator
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $gameId = GameId::create();

        $parameters = json_decode($request->getContent(), true);

        $violations = $this->createGameRequestValidator->validate($parameters ?? []);

        if (count($violations) > 0) {
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch(new CreateGameCommand($gameId, $parameters['colorCode'] ?? null));

        return new JsonResponse(['id' => (string) $gameId], Response::HTTP_CREATED);
    }
}
