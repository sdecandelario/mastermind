<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode\Exception;

use App\MastermindContext\Domain\ColorCode\ColorCodeValue;
use App\Shared\Domain\Exception\DomainException;

final class InvalidColorCodeValueException extends DomainException
{
    public static function create(): InvalidColorCodeValueException
    {
        $validValues = array_map(function (ColorCodeValue $colorCodeValue) {
            return $colorCodeValue->value;
        }, ColorCodeValue::cases());

        $validValuesString = implode(', ', $validValues);

        return new self("Invalid combination, the allowed values accepted are ($validValuesString)");
    }
}
