<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Command;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;

final class CreateGameCommandHandler
{
    public function __construct(private readonly GameRepositoryInterface $gameRepository)
    {
    }

    public function __invoke(CreateGameCommand $command): void
    {
        $colorCode = ColorCode::random();

        if ($command->colorCode()) {
            $colorCode = ColorCode::createFromString($command->colorCode());
        }

        $game = Game::create($command->gameId(), $colorCode);

        $this->gameRepository->save($game);
    }
}
