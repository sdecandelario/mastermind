<?php

namespace App\Tests\Unit\MastermindContext\Application\Game\Command;

use App\MastermindContext\Application\Game\Command\CreateGameCommand;
use App\MastermindContext\Application\Game\Command\CreateGameCommandHandler;
use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateGameCommandHandlerTest extends TestCase
{
    private GameRepositoryInterface|MockObject $gameRepository;
    private CreateGameCommandHandler $sut;

    protected function setUp(): void
    {
        $this->gameRepository = $this->createMock(GameRepositoryInterface::class);

        $this->sut = new CreateGameCommandHandler(
            $this->gameRepository
        );
    }

    public function testSuccess()
    {
        $gameId = GameId::create();

        $this->gameRepository
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(function (Game $game) use ($gameId) {
                self::assertSame($gameId, $game->id());
                self::assertTrue($game->isStarted());
                return true;
            }));

        $this->sut->__invoke(new CreateGameCommand($gameId));
    }
}
