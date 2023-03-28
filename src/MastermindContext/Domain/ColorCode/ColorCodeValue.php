<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

enum ColorCodeValue: string
{
    case Red = 'R';
    case Yellow = 'Y';
    case Green = 'G';
    case Blue = 'B';

    public static function random(): ColorCodeValue
    {
        $colorIndex = random_int(0, 3);

        return self::cases()[$colorIndex];
    }
}
