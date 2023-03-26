<?php

namespace App\Shared\Domain\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $command): QueryResultInterface;
}
