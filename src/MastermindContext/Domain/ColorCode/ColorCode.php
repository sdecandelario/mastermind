<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

final class ColorCode
{
    private function __construct(private readonly string $value)
    {
    }

    /**
     * @throws InvalidColorCodeLengthException
     */
    public static function create(string $value): ColorCode
    {
        self::checkLength($value);

        return new self($value);
    }

    /**
     * @throws InvalidColorCodeLengthException
     */
    private static function checkLength(string $value): void
    {
        if(strlen($value)!==4){
            throw InvalidColorCodeLengthException::create();
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
