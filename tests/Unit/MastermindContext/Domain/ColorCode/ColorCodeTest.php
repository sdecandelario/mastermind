<?php


namespace App\Tests\Unit\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\ColorCode\InvalidColorCodeLengthException;
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
}
