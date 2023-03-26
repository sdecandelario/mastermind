<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\ColorCode;

final class ColorCode
{
    private function __construct(private readonly string $value)
    {
    }

    public static function create(string $value): ColorCode
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
