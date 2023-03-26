<?php

declare(strict_types=1);

namespace App\Shared\Domain\Query;

interface QueryResultInterface
{
    public function toArray(): array;
}
