<?php

namespace App\Tests\Unit\MastermindContext\Application\Game\Query;

use App\MastermindContext\Application\Game\Query\GetGameQuery;
use App\MastermindContext\Application\Game\Query\GetGameQueryHandler;
use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameStatus;
use App\MastermindContext\Domain\Game\Service\GameFinder;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\GuessBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetGameQueryHandlerTest extends TestCase
{
    private readonly GameFinder|MockObject $gameFinder;
    private GetGameQueryHandler $sut;

    protected function setUp(): void
    {
        $this->gameFinder = $this->createMock(GameFinder::class);

        $this->sut = new GetGameQueryHandler(
            $this->gameFinder
        );
    }

    public function testGetGameRetrievedWithNoGuesses()
    {
        $gameId = GameId::create();
        $game = GameBuilder::create()->withId($gameId)->build();

        $this->gameFinder
            ->expects(self::once())
            ->method('findOrFail')
            ->with($gameId)
            ->willReturn($game);

        $result = $this->sut->__invoke(new GetGameQuery($gameId));

        self::assertSame([
            'id' => $gameId->value(),
            'status' => $game->status()->value,
            'guesses' => [],
        ], $result->toArray());
    }

    public function testGetGameRetrievedWithOneGuess()
    {
        $gameId = GameId::create();
        $game = GameBuilder::create()
            ->withId($gameId)
            ->withColorCode(ColorCode::createFromString('YYYY'))
            ->build();
        $guess = GuessBuilder::create($game)
            ->withColorCode(ColorCode::createFromString('BBBB'))
            ->build();
        $game->addGuess($guess);

        $this->gameFinder
            ->expects(self::once())
            ->method('findOrFail')
            ->with($gameId)
            ->willReturn($game);

        $result = $this->sut->__invoke(new GetGameQuery($gameId));

        self::assertSame([
            'id' => $gameId->value(),
            'status' => GameStatus::InProgress->value,
            'guesses' => [
                [
                    'id' => $guess->id()->value(),
                    'colorCode' => 'BBBB',
                    'blackPeg' => 0,
                    'whitePeg' => 0,
                ],
            ],
        ], $result->toArray());
    }

    public function gameFinishedStatusProvider(): array
    {
        return [
            [GameStatus::Won],
            [GameStatus::Lost],
        ];
    }

    /**
     * @dataProvider gameFinishedStatusProvider
     */
    public function testGameFinishedShowSecretCode(GameStatus $status)
    {
        $gameId = GameId::create();
        $game = GameBuilder::create()
            ->withId($gameId)
            ->withStatus($status)
            ->build();

        $this->gameFinder
            ->expects(self::once())
            ->method('findOrFail')
            ->with($gameId)
            ->willReturn($game);

        $result = $this->sut->__invoke(new GetGameQuery($gameId));

        self::assertSame([
            'id' => $gameId->value(),
            'status' => $status->value,
            'secretCode' => $game->secretCode()->value(),
            'guesses' => [],
        ], $result->toArray());
    }
}
