<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Query;

use App\Shared\Domain\Query\QueryBusInterface;
use App\Shared\Domain\Query\QueryInterface;
use App\Shared\Domain\Query\QueryResultInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerQueryBus implements QueryBusInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function ask(QueryInterface $command): QueryResultInterface
    {
        $this->messageBus->dispatch($command);
    }
}
