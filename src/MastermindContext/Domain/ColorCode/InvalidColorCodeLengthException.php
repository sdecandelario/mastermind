<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

final class InvalidColorCodeLengthException extends \Exception
{
    public static function create(): InvalidColorCodeLengthException
    {
        return new self('Invalid length, should be exactly 4 characters');
    }
}
