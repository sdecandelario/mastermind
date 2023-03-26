<?php


namespace App\Tests\Unit\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\ColorCode\InvalidColorCodeLengthException;
use App\MastermindContext\Domain\ColorCode\InvalidColorCodeValuesException;
use PHPUnit\Framework\TestCase;

class ColorCodeTest extends TestCase
{
    public function invalidLengthProvider(): array
    {
        return [
            ['1'],
            ['11'],
            ['111'],
            ['111111'],
        ];
    }

    /**
     * @dataProvider invalidLengthProvider
     */
    public function testInvalidLengthThrowAnException(string $value)
    {
        $this->expectException(InvalidColorCodeLengthException::class);
        $this->expectExceptionMessage('Invalid length, should be exactly 4 characters');

        ColorCode::create($value);
    }

    public function invalidValuesProvider(): array
    {
        return [
            ['1111'],
            ['R111'],
            ['1R11'],
            ['11R1'],
            ['111R'],
            ['Y111'],
            ['G111'],
            ['B111'],
            ['RYG1'],
        ];
    }

    /**
     * @dataProvider invalidValuesProvider
     */
    public function testInvalidValuesForColorCodeThrowAnException(string $value)
    {
        $this->expectException(InvalidColorCodeValuesException::class);
        $this->expectExceptionMessage('Invalid values, the only values accepted are R, G, B or Y ');

        ColorCode::create($value);
    }
}
