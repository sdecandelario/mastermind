<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Command;

use App\Shared\Domain\Command\CommandBusInterface;
use App\Shared\Domain\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerCommandBus implements CommandBusInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
