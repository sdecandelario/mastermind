<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Symfony\Component\Uid\Uuid;

class AbstractUuid
{
    private Uuid $id;

    protected function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function value(): string
    {
        return $this->id->toRfc4122();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    protected static function isValid(string $value): bool
    {
        return Uuid::isValid($value);
    }
}
