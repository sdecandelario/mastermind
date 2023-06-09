<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Query;

use App\Shared\Domain\Query\QueryBusInterface;
use App\Shared\Domain\Query\QueryInterface;
use App\Shared\Domain\Query\QueryResultInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyMessengerQueryBus implements QueryBusInterface
{
    public function __construct(private readonly MessageBusInterface $queryBus)
    {
    }

    public function ask(QueryInterface $command): QueryResultInterface
    {
        $envelope = $this->queryBus->dispatch($command);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
