<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Application\Game\Command\CreateGameCommand;
use App\MastermindContext\Domain\Game\GameId;
use App\Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateGameController
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function __invoke(): Response
    {
        $gameId = GameId::create();

        $this->commandBus->dispatch(new CreateGameCommand($gameId->id()->toRfc4122()));

        return new JsonResponse(['id' => $gameId->id()->toRfc4122()], Response::HTTP_CREATED);
    }
}
