<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidGameIdException extends DomainException
{
    public static function withId(string $id): InvalidGameIdException
    {
        return new self("The id '$id' is not a valid game id");
    }
}
