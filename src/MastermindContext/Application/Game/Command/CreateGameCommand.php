<?php

declare(strict_types=1);

namespace App\MastermindContext\Application\Game\Command;

use App\Shared\Domain\Command\CommandInterface;

final class CreateGameCommand implements CommandInterface
{
    public function __construct(private readonly string $gameId)
    {
    }

    public function gameId(): string
    {
        return $this->gameId;
    }
}
