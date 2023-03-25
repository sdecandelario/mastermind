<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Command;

use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;

final class CreateGameCommandHandler
{
    public function __construct(private readonly GameRepositoryInterface $gameRepository)
    {
    }

    public function __invoke(CreateGameCommand $command): void
    {
        $game = Game::create(GameId::createFromString($command->gameId()));

        $this->gameRepository->save($game);
    }
}
