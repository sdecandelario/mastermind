<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Guess\Http;

use App\MastermindContext\Application\Guess\Command\MakeGuessCommand;
use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Guess\GuessId;
use App\Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class MakeGuessController
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    /**
     * @throws InvalidGameIdException
     */
    public function __invoke(string $id): Response
    {
        $guessId = GuessId::create();

        $this->commandBus->dispatch(new MakeGuessCommand($guessId, GameId::createFromString($id)));

        return new JsonResponse([
            'id' => $guessId->id()->__toString(),
        ], Response::HTTP_CREATED);
    }
}
