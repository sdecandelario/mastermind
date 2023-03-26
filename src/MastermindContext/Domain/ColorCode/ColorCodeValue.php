<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

enum ColorCodeValue: string
{
    case Red = 'R';
    case Yellow = 'Y';
    case Green = 'G';
    case Blue='B';
}
