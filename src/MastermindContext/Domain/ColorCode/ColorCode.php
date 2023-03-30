<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

use App\MastermindContext\Domain\ColorCode\Exception\InvalidColorCodeValueException;
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
     * @throws InvalidColorCodeValueException
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
     * @throws InvalidColorCodeValueException
     */
    private static function validateString(string $colorCode): void
    {
        for ($i = 0; $i < mb_strlen($colorCode); ++$i) {
            ColorCodeValue::assertIsValid($colorCode[$i]);
        }
    }

    public function calculateBlackPegs(ColorCode $colorCode): int
    {
        $secretCodeCombination = $this->toArray();
        $blackPegs = 0;

        foreach ($colorCode->toArray() as $key => $value) {
            if ($value === $secretCodeCombination[$key]) {
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

    public function toArray(): array
    {
        return [
            $this->firstValue->value,
            $this->secondValue->value,
            $this->thirdValue->value,
            $this->fourthValue->value,
        ];
    }
}
