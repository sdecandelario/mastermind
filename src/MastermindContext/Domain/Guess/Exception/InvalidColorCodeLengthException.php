<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Guess\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidColorCodeLengthException extends DomainException
{
    public static function create(): InvalidColorCodeLengthException
    {
        return new self('Invalid length for color code, must be exactly 4 characters');
    }
}
