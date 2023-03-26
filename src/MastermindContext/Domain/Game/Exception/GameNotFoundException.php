<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game\Exception;

use App\MastermindContext\Domain\Game\GameId;
use App\Shared\Domain\Exception\NotFoundException;

final class GameNotFoundException extends NotFoundException
{
    public static function withId(GameId $gameId): GameNotFoundException
    {
        return new self("Game with id {$gameId->__toString()} not found");
    }
}
