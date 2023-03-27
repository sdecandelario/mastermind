<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\Guess\Exception\InvalidColorCodeCombinationException;
use App\MastermindContext\Domain\Guess\Exception\InvalidColorCodeLengthException;

final class ColorCode
{
    private function __construct(
        private readonly ColorCodeValue $firstValue,
        private readonly ColorCodeValue $secondValue,
        private readonly ColorCodeValue $thirdValue,
        private readonly ColorCodeValue $fourthValue,
    ) {
    }

    public static function create(
        ColorCodeValue $firstValue,
        ColorCodeValue $secondValue,
        ColorCodeValue $thirdValue,
        ColorCodeValue $fourthValue
    ): ColorCode {
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
        $colorIndex = random_int(0, 3);

        return ColorCodeValue::cases()[$colorIndex];
    }

    /**
     * @throws InvalidColorCodeCombinationException
     * @throws InvalidColorCodeLengthException
     */
    public static function createFromString(string $colorCode): ColorCode
    {
        self::validateLength($colorCode);

        self::validateString($colorCode);

        return new self(
            ColorCodeValue::from($colorCode[0]),
            ColorCodeValue::from($colorCode[1]),
            ColorCodeValue::from($colorCode[2]),
            ColorCodeValue::from($colorCode[3])
        );
    }

    /**
     * @throws InvalidColorCodeLengthException
     */
    public static function validateLength(string $colorCode): void
    {
        if (4 !== mb_strlen($colorCode)) {
            throw InvalidColorCodeLengthException::create();
        }
    }

    /**
     * @throws InvalidColorCodeCombinationException
     */
    private static function validateString(string $colorCode): void
    {
        $validValues = [
            ColorCodeValue::Green->value,
            ColorCodeValue::Yellow->value,
            ColorCodeValue::Blue->value,
            ColorCodeValue::Red->value,
        ];

        for ($i = 0; $i < mb_strlen($colorCode); ++$i) {
            if (!in_array($colorCode[$i], $validValues)) {
                throw InvalidColorCodeCombinationException::create();
            }
        }
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
