<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Guess\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidGuessIdException extends DomainException
{
    public static function withId(string $id): InvalidGuessIdException
    {
        return new self("The id '$id' is not a guess id");
    }
}
