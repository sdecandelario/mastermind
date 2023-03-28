<?php

namespace App\Tests\Unit\MastermindContext\Domain\Game\Service;

use App\MastermindContext\Domain\Game\Exception\GameNotFoundException;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\MastermindContext\Domain\Game\Service\GameFinder;
use App\Tests\Builder\GameBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GameFinderTest extends TestCase
{
    private GameRepositoryInterface|MockObject $gameRepository;
    private GameFinder $sut;

    protected function setUp(): void
    {
        $this->gameRepository = $this->createMock(GameRepositoryInterface::class);

        $this->sut = new GameFinder($this->gameRepository);
    }

    public function testGameNotFound()
    {
        $gameId = GameId::create();

        $this->gameRepository
            ->expects(self::once())
            ->method('findById');

        $this->expectException(GameNotFoundException::class);
        $this->expectExceptionMessage("Game with id {$gameId->value()} not found");

        $this->sut->findOrFail($gameId);
    }

    public function testGameFound()
    {
        $gameId = GameId::create();
        $game = GameBuilder::create()->withId($gameId)->build();

        $this->gameRepository
            ->expects(self::once())
            ->method('findById')
        ->willReturn($game);

        $result = $this->sut->findOrFail($gameId);

        self::assertSame($game, $result);
    }
}
