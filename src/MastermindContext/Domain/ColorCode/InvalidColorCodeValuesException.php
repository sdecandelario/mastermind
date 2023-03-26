<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

final class InvalidColorCodeValuesException extends \Exception
{
    public static function create(): InvalidColorCodeValuesException
    {
        return new self('Invalid values, the only values accepted are R, G, B or Y ');
    }
}
