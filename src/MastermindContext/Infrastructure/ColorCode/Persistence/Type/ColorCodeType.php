<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\ColorCode\Persistence\Type;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\ColorCode\ColorCodeValue;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class ColorCodeType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ColorCode
    {
        return ColorCode::create(
            ColorCodeValue::from($value[0]),
            ColorCodeValue::from($value[1]),
            ColorCodeValue::from($value[2]),
            ColorCodeValue::from($value[3]),
        );
    }

    public function getName(): string
    {
        return 'color_code';
    }
}
