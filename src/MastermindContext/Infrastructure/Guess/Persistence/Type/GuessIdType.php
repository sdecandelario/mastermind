<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Guess\Persistence\Type;

use App\MastermindContext\Domain\Guess\GuessId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class GuessIdType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): GuessId
    {
        return GuessId::createFromString($value);
    }

    public function getName(): string
    {
        return 'guess_id';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }
}
