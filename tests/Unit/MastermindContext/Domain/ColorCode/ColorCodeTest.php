<?php

namespace App\Tests\Unit\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\ColorCode\ColorCodeValue;
use PHPUnit\Framework\TestCase;

class ColorCodeTest extends TestCase
{
    public function testCreateByValuesSuccess()
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

    public function testCreateByRandomSuccess()
    {
        $result = ColorCode::random();

        self::assertTrue(4 === mb_strlen($result->value()));
    }

    public function colorCodeWithBlackPegs(): array
    {
        return [
            [ColorCode::createFromString('BGYR'), ColorCode::createFromString('RYGB'), 0],
            [ColorCode::createFromString('RRRR'), ColorCode::createFromString('RYGB'), 1],
            [ColorCode::createFromString('RYRR'), ColorCode::createFromString('RYGB'), 2],
            [ColorCode::createFromString('RYGR'), ColorCode::createFromString('RYGB'), 3],
            [ColorCode::createFromString('RYGB'), ColorCode::createFromString('RYGB'), 4],
        ];
    }

    /**
     * @dataProvider colorCodeWithBlackPegs
     */
    public function testCalculateBlackPegs(ColorCode $colorCode, ColorCode $secretColorCode, int $blackPegs)
    {
        $result = $colorCode->calculateBlackPegs($secretColorCode);

        self::assertSame($blackPegs, $result);
    }

    public function colorCodeWithWhitePegs(): array
    {
        return [
            [ColorCode::createFromString('RYGB'), ColorCode::createFromString('RYGB'), 0],
            [ColorCode::createFromString('BBBB'), ColorCode::createFromString('RYGB'), 1],
            [ColorCode::createFromString('GBBB'), ColorCode::createFromString('RYGB'), 2],
            [ColorCode::createFromString('BRYB'), ColorCode::createFromString('RYGB'), 3],
            [ColorCode::createFromString('BRYG'), ColorCode::createFromString('RYGB'), 4],
        ];
    }

    /**
     * @dataProvider colorCodeWithWhitePegs
     */
    public function testCalculateWhitePegs(ColorCode $colorCode, ColorCode $secretColorCode, int $whitePegs)
    {
        $result = $colorCode->calculateWhitePegs($secretColorCode);

        self::assertSame($whitePegs, $result);
    }
}
