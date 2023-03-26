<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Persistence\Type;

use App\MastermindContext\Domain\Game\GameId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class GameIdType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): GameId
    {
        return GameId::createFromString($value);
    }

    public function getName(): string
    {
        return 'game_id';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }
}
