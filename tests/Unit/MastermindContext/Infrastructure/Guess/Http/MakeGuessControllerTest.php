<?php

namespace App\Tests\Unit\MastermindContext\Infrastructure\Guess\Http;

use App\MastermindContext\Application\Guess\Command\MakeGuessCommand;
use App\MastermindContext\Domain\Game\Exception\InvalidGameIdException;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Infrastructure\Guess\Http\MakeGuessController;
use App\MastermindContext\Infrastructure\Guess\Validator\Request\MakeGuessRequestValidator;
use App\Shared\Domain\Command\CommandBusInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class MakeGuessControllerTest extends TestCase
{
    private CommandBusInterface|MockObject $commandBus;
    private MakeGuessRequestValidator|MockObject $makeGuessRequestValidator;
    private Request|MockObject $request;
    private MakeGuessController $sut;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->makeGuessRequestValidator = $this->createMock(MakeGuessRequestValidator::class);
        $this->request = $this->createMock(Request::class);

        $this->sut = new MakeGuessController(
            $this->commandBus,
            $this->makeGuessRequestValidator
        );
    }

    public function testInvalidRequestReturnBadRequestResponse()
    {
        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('');

        $this->makeGuessRequestValidator
            ->expects(self::once())
            ->method('validate')
            ->willReturn([
                ['error' => 'invalid field'],
            ]);

        $response = $this->sut->__invoke($this->request, 'gameId');

        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertSame('[{"error":"invalid field"}]', $response->getContent());
    }

    public function testInvalidGameIdThrowAnException()
    {
        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('');

        $this->makeGuessRequestValidator
            ->expects(self::once())
            ->method('validate')
            ->willReturn([]);

        $this->expectException(InvalidGameIdException::class);
        $this->expectExceptionMessage("The id 'gameId' is not a game id");

        $this->sut->__invoke($this->request, 'gameId');
    }

    public function testSuccess()
    {
        $gameId = GameId::create();

        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('{"colorCode":"RRRR"}');

        $this->makeGuessRequestValidator
            ->expects(self::once())
            ->method('validate')
            ->willReturn([]);

        $this->commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function (MakeGuessCommand $command) use ($gameId) {
                    self::assertEquals($gameId, $command->gameId());
                    self::assertSame('RRRR', $command->colorCode());

                    return true;
                })
            );

        $result = $this->sut->__invoke($this->request, $gameId->value());

        $jsonResponse = json_decode($result->getContent(), true);

        self::assertSame(Response::HTTP_CREATED, $result->getStatusCode());
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertTrue(Uuid::isValid($jsonResponse['id']));
    }
}
