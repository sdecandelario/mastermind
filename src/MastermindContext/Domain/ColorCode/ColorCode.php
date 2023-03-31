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
    private static function validateLength(string $colorCode): void
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

    public function calculateBlackPegs(ColorCode $secretCode): int
    {
        $secretCodeValues = $secretCode->toArray();
        $guessValues = $this->toArray();
        $blackPegs = 0;

        foreach ($guessValues as $key => $value) {
            if ($value === $secretCodeValues[$key]) {
                ++$blackPegs;
            }
        }

        return $blackPegs;
    }

    public function calculateWhitePegs(ColorCode $secretColor): int
    {
        $secretColorValues = $secretColor->toArray();
        $guessValues = $this->toArray();
        $whitePegs = 0;
        $matchedColors = [];

        foreach ($guessValues as $key => $value) {
            if ($value === $secretColorValues[$key]) {
                unset($secretColorValues[$key]);
                unset($guessValues[$key]);
            }
        }

        foreach ($guessValues as $value) {
            if (!array_key_exists($value, $matchedColors)) {
                $matchedColors[$value] = 0;
            }

            if (!in_array($value, $secretColorValues)) {
                continue;
            }

            $matches = $matchedColors[$value];

            $repetitions = array_count_values($secretColorValues);

            if ($matches >= $repetitions[$value]) {
                continue;
            }

            ++$whitePegs;
            ++$matchedColors[$value];
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
