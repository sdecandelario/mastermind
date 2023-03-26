<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use Symfony\Component\Uid\Uuid;

final class GameId
{
    private Uuid $id;

    private function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public static function create(): GameId
    {
        return new self(Uuid::v4());
    }

    /**
     * @throws InvalidGameIdException
     */
    public static function createFromString(string $id): GameId
    {
        self::assertIdIsValid($id);

        return new self(Uuid::fromString($id));
    }

    /**
     * @throws InvalidGameIdException
     */
    private static function assertIdIsValid(string $id): void
    {
        if (false === Uuid::isValid($id)) {
            throw InvalidGameIdException::withId($id);
        }
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id->toRfc4122();
    }
}
