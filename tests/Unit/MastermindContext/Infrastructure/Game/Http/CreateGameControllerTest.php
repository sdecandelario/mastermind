<?php

namespace App\Tests\Unit\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Application\Game\Command\CreateGameCommand;
use App\MastermindContext\Infrastructure\Game\Http\CreateGameController;
use App\MastermindContext\Infrastructure\Game\Validator\Request\CreateGameRequestValidator;
use App\Shared\Domain\Command\CommandBusInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class CreateGameControllerTest extends TestCase
{
    private CommandBusInterface|MockObject $commandBus;
    private CreateGameRequestValidator|MockObject $createGameRequestValidator;
    private Request|MockObject $request;
    private CreateGameController $sut;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->createGameRequestValidator = $this->createMock(CreateGameRequestValidator::class);
        $this->request = $this->createMock(Request::class);

        $this->sut = new CreateGameController(
            $this->commandBus,
            $this->createGameRequestValidator
        );
    }

    public function testInvalidRequestReturnBadRequestResponse(): void
    {
        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('');

        $this->createGameRequestValidator
            ->expects(self::once())
            ->method('validate')
            ->willReturn([
                ['error' => 'invalid field'],
            ]);

        $response = $this->sut->__invoke($this->request);

        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertSame('[{"error":"invalid field"}]', $response->getContent());
    }

    public function testSuccess(): void
    {
        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('');

        $this->commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(CreateGameCommand::class));

        $response = $this->sut->__invoke($this->request);

        $jsonResponse = json_decode($response->getContent(), true);

        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertTrue(Uuid::isValid($jsonResponse['id']));
    }
}
