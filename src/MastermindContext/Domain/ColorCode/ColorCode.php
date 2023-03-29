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
            ColorCodeValue::random(),
            ColorCodeValue::random(),
            ColorCodeValue::random(),
            ColorCodeValue::random(),
        );
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
        $validValues = array_map(function (ColorCodeValue $colorCodeValue) {
            return $colorCodeValue->value;
        }, ColorCodeValue::cases());

        for ($i = 0; $i < mb_strlen($colorCode); ++$i) {
            if (!in_array($colorCode[$i], $validValues)) {
                throw InvalidColorCodeCombinationException::create();
            }
        }
    }

    public function calculateBlackPegs(ColorCode $colorCode): int
    {
        $valueAsString = $this->value();
        $colorCodeAsString = $colorCode->value();
        $blackPegs = 0;

        for ($i = 0; $i < 4; ++$i) {
            $position = mb_strpos($colorCodeAsString, $valueAsString[$i]);

            if ($position === $i) {
                ++$blackPegs;
            }
        }

        return $blackPegs;
    }

    public function calculateWhitePegs(ColorCode $colorCode): int
    {
        $valueAsString = $this->value();
        $colorCodeAsString = $colorCode->value();
        $whitePegs = 0;

        for ($i = 0; $i < 4; ++$i) {
            $position = mb_strpos($colorCodeAsString, $valueAsString[$i]);

            if (false !== $position && $position !== $i) {
                ++$whitePegs;
                $colorCodeAsString = substr_replace($colorCodeAsString, '', $position, 1);
            }
        }

        return $whitePegs;
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
