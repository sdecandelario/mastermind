<?php

namespace App\Tests\Unit\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Application\Game\Command\CreateGameCommand;
use App\MastermindContext\Infrastructure\Game\Http\CreateGameController;
use App\Shared\Domain\Command\CommandBusInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class CreateGameControllerTest extends TestCase
{
    private CommandBusInterface|MockObject $commandBus;
    private CreateGameController $sut;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBusInterface::class);

        $this->sut = new CreateGameController(
            $this->commandBus
        );
    }

    public function testSuccess()
    {
        $this->commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(CreateGameCommand::class));

        $response = $this->sut->__invoke();

        $jsonResponse = json_decode($response->getContent(), true);

        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertTrue(Uuid::isValid($jsonResponse['id']));
    }
}
