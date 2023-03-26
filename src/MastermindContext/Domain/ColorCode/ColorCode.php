<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

final class ColorCode
{
    private function __construct(
        private readonly ColorCodeValue $firstValue,
        private readonly ColorCodeValue $secondValue,
        private readonly ColorCodeValue $thirdValue,
        private readonly ColorCodeValue $fourthValue,
    )
    {
    }

    public static function create(
        ColorCodeValue $firstValue,
        ColorCodeValue $secondValue,
        ColorCodeValue $thirdValue,
        ColorCodeValue $fourthValue
    ): ColorCode
    {
        return new self($firstValue, $secondValue, $thirdValue, $fourthValue);
    }

    public static function random(): ColorCode
    {
        return new self(
            self::createRandomColor(),
            self::createRandomColor(),
            self::createRandomColor(),
            self::createRandomColor(),
        );
    }

    private static function createRandomColor(): ColorCodeValue
    {
        $colorIndex = random_int(0,3);

        return ColorCodeValue::cases()[$colorIndex];
    }

    public function value(): string
    {
        return sprintf(
            '%s%s%s%s',
            $this->firstValue->value,
            $this->secondValue->value,
            $this->thirdValue->value,
            $this->fourthValue->value
        );
    }
}
