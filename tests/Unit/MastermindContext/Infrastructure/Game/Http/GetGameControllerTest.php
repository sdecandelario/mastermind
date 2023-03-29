<?php

namespace App\Tests\Unit\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Application\Game\Query\GetGameQuery;
use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Infrastructure\Game\Http\GetGameController;
use App\Shared\Domain\Query\QueryBusInterface;
use App\Shared\Domain\Query\QueryResultInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetGameControllerTest extends TestCase
{
    private MockObject|QueryBusInterface $queryBus;
    private GetGameController $sut;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBusInterface::class);

        $this->sut = new GetGameController(
            $this->queryBus
        );
    }

    public function testInvalidGameIdThrowAnException()
    {
        $this->expectException(InvalidGameIdException::class);
        $this->expectExceptionMessage("The id 'invalidGameId' is not a valid game id");

        $this->sut->__invoke('invalidGameId');
    }

    public function testGetGameControllerSuccess()
    {
        $queryResult = $this->createMock(QueryResultInterface::class);

        $id = GameId::create()->value();

        $this->queryBus
            ->expects(self::once())
            ->method('ask')
            ->with(new GetGameQuery(GameId::createFromString($id)))
            ->willReturn($queryResult);

        $queryResult
            ->expects(self::once())
            ->method('toArray')
            ->willReturn([]);

        $result = $this->sut->__invoke($id);

        self::assertSame(Response::HTTP_OK, $result->getStatusCode());
        self::assertSame('[]', $result->getContent());

    }
}
