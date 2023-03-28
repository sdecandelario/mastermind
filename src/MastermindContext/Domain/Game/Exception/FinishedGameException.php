<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game\Exception;

use App\MastermindContext\Domain\Game\GameId;
use App\Shared\Domain\Exception\DomainException;

final class FinishedGameException extends DomainException
{
    public static function createWithGameId(GameId $gameId): FinishedGameException
    {
        return new self("The game {$gameId->value()} is finished, not allow more guesses");
    }
}
