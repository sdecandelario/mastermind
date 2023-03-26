<?php

namespace App\Tests\Unit\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\ColorCode\ColorCodeValue;
use PHPUnit\Framework\TestCase;

class ColorCodeTest extends TestCase
{
    public function testCreateSuccess()
    {
        $expectedColorCode = sprintf(
            '%s%s%s%s',
            ColorCodeValue::Green->value,
            ColorCodeValue::Red->value,
            ColorCodeValue::Blue->value,
            ColorCodeValue::Yellow->value
        );

        $colorCode = ColorCode::create(
            ColorCodeValue::Green,
            ColorCodeValue::Red,
            ColorCodeValue::Blue,
            ColorCodeValue::Yellow
        );

        self::assertSame($expectedColorCode, $colorCode->value());
    }
}
