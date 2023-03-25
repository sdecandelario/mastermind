<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

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

    public static function createFromString(string $uuid): GameId
    {
        return new self(Uuid::fromString($uuid));
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
