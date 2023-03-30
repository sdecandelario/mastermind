<?php

namespace App\Tests\Unit\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\ColorCode\ColorCodeValue;
use App\MastermindContext\Domain\ColorCode\Exception\InvalidColorCodeValueException;
use App\MastermindContext\Domain\Guess\Exception\InvalidColorCodeLengthException;
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

    public function testCreateByRandomSuccess()
    {
        $result = ColorCode::random();

        self::assertTrue(4 === mb_strlen($result->value()));
    }

    public function invalidStringLength(): array
    {
        return [
            ['1'],
            ['11'],
            ['111'],
            ['11111'],
        ];
    }

    /**
     * @dataProvider invalidStringLength
     */
    public function testCreateFromStringWithInvalidLength(string $colorCode)
    {
        $this->expectException(InvalidColorCodeLengthException::class);
        $this->expectExceptionMessage('Invalid length for color code, must be exactly 4 characters');

        ColorCode::createFromString($colorCode);
    }

    public function testCreateFromStringWithInvalidColorCode()
    {
        $this->expectException(InvalidColorCodeValueException::class);
        $this->expectExceptionMessage('Invalid combination, the allowed values accepted are (R, Y, G, B, W, O)');

        ColorCode::createFromString('1234');
    }

    public function testCreateFromStringSuccess()
    {
        $colorCode = ColorCode::createFromString('RYGB');

        self::assertSame('RYGB', $colorCode->value());
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
            [ColorCode::createFromString('BRBR'), ColorCode::createFromString('RBRB'), 4],
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

    public function testGetAsString()
    {
        $colorCode = ColorCode::create(
            ColorCodeValue::Red,
            ColorCodeValue::Yellow,
            ColorCodeValue::Green,
            ColorCodeValue::Blue,
        );

        self::assertSame('RYGB', $colorCode->value());
    }
}
