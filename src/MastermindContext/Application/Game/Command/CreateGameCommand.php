<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Command;

use App\MastermindContext\Domain\Game\GameId;
use App\Shared\Domain\Command\CommandInterface;

final class CreateGameCommand implements CommandInterface
{
    public function __construct(private readonly GameId $gameId)
    {
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }
}
