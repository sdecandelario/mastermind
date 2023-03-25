<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Persistence\Type;

use App\MastermindContext\Domain\Game\GameStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class GameStatusType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): GameStatus
    {
        return GameStatus::tryFrom($value);
    }

    public function getName(): string
    {
        return 'game_status';
    }
}
