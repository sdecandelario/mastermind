<?php

namespace App\Tests\Unit\Shared\Infrastructure\Query;

use App\Shared\Domain\Query\QueryInterface;
use App\Shared\Domain\Query\QueryResultInterface;
use App\Shared\Infrastructure\Query\SymfonyMessengerQueryBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyMessengerQueryBusTest extends TestCase
{
    private MessageBusInterface|MockObject $queryBus;
    private SymfonyMessengerQueryBus $sut;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(MessageBusInterface::class);

        $this->sut = new SymfonyMessengerQueryBus(
            $this->queryBus
        );
    }

    public function testAskQuery()
    {
        $query = $this->createMock(QueryInterface::class);
        $queryResult = $this->createMock(QueryResultInterface::class);
        $stamp = new HandledStamp($queryResult, 'test');
        $envelope = new Envelope(new \stdClass(), [$stamp]);

        $this->queryBus
            ->expects(self::once())
            ->method('dispatch')
            ->with($query)
            ->willReturn($envelope);

        $result = $this->sut->ask($query);

        self::assertSame($queryResult, $result);
    }
}
