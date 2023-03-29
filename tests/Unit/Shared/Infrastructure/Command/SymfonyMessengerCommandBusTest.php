<?php

namespace App\Tests\Unit\Shared\Infrastructure\Command;

use App\Shared\Domain\Command\CommandInterface;
use App\Shared\Infrastructure\Command\SymfonyMessengerCommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyMessengerCommandBusTest extends TestCase
{
    private MessageBusInterface|MockObject $messageBus;
    private SymfonyMessengerCommandBus $sut;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->sut = new SymfonyMessengerCommandBus(
            $this->messageBus
        );
    }

    public function testDispatchCommand()
    {
        $command = $this->createMock(CommandInterface::class);
        $envelope = new Envelope(new \stdClass());

        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($envelope);

        $this->sut->dispatch($command);
    }
}
