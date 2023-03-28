<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use Symfony\Component\Uid\Uuid;

class AbstractUuid
{
    private Uuid $id;

    protected function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @throws InvalidGameIdException
     */
    protected static function assertIdIsValid(string $id): void
    {
        if (false === Uuid::isValid($id)) {
            throw InvalidGameIdException::withId($id);
        }
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function value()
    {
        return $this->id->toRfc4122();
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
