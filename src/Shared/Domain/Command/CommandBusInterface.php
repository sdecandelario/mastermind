<?php

namespace App\Shared\Domain\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
