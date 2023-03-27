<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Guess\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidColorCodeCombinationException extends DomainException
{
    public static function create(): InvalidColorCodeCombinationException
    {
        return new self('The combination is wrong, the only values accepted are (R, Y, G and B)');
    }
}
