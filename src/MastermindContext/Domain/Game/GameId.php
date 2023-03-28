<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use App\Shared\Domain\ValueObject\AbstractUuid;
use Symfony\Component\Uid\Uuid;

final class GameId extends AbstractUuid
{
    public static function create(): self
    {
        return new self(Uuid::v4());
    }

    /**
     * @throws InvalidGameIdException
     */
    public static function createFromString(string $id): self
    {
        self::assertIdIsValid($id);

        return new self(Uuid::fromString($id));
    }

    /**
     * @throws InvalidGameIdException
     */
    protected static function assertIdIsValid(string $id): void
    {
        if (!self::isValid($id)) {
            throw InvalidGameIdException::withId($id);
        }
    }
}
