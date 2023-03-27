<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Guess\Http;

use App\MastermindContext\Application\Guess\Command\MakeGuessCommand;
use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Guess\GuessId;
use App\MastermindContext\Infrastructure\Guess\Validator\Request\MakeGuessRequestValidator;
use App\Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MakeGuessController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly MakeGuessRequestValidator $makeGuessRequestValidator
    ) {
    }

    /**
     * @throws InvalidGameIdException
     */
    public function __invoke(Request $request, string $id): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $violations = $this->makeGuessRequestValidator->validate($parameters ?? []);

        if (count($violations) > 0) {
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }

        $guessId = GuessId::create();

        $this->commandBus->dispatch(
            new MakeGuessCommand(
                $guessId,
                GameId::createFromString($id),
                $parameters['colorCode']
            )
        );

        return new JsonResponse([
            'id' => $guessId->id()->__toString(),
        ], Response::HTTP_CREATED);
    }
}
