<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

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
}
