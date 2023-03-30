<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\Exception\InvalidColorCodeValueException;

enum ColorCodeValue: string
{
    case Red = 'R';
    case Yellow = 'Y';
    case Green = 'G';
    case Blue = 'B';
    case White = 'W';
    case Orange = 'O';

    public static function random(): ColorCodeValue
    {
        $colorIndex = random_int(0, 5);

        return self::cases()[$colorIndex];
    }

    /**
     * @throws InvalidColorCodeValueException
     */
    public static function assertIsValid(string $value): void
    {
        if (null === self::tryFrom($value)) {
            throw InvalidColorCodeValueException::create();
        }
    }
}
