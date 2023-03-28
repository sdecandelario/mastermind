<?php

namespace App\Tests\Unit\MastermindContext\Domain\Game\Service;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\Exception\FinishedGameException;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\MastermindContext\Domain\Game\GameStatus;
use App\MastermindContext\Domain\Game\Service\AddGuess;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\GuessBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddGuessTest extends TestCase
{
    private GameRepositoryInterface|MockObject $gameRepository;
    private AddGuess $sut;

    protected function setUp(): void
    {
        $this->gameRepository = $this->createMock(GameRepositoryInterface::class);

        $this->sut = new AddGuess(
            $this->gameRepository
        );
    }

    public function finishedGameStatusProvider(): array
    {
        return [
            [GameStatus::Lost],
            [GameStatus::Won],
        ];
    }

    /**
     * @dataProvider finishedGameStatusProvider
     */
    public function testGameIsFinishedThrowAnException(GameStatus $status)
    {
        $game = GameBuilder::create()->withStatus($status)->build();
        $guess = GuessBuilder::create($game)->build();

        $this->expectException(FinishedGameException::class);
        $this->expectExceptionMessage("The game {$game->id()->value()} is finished, not allow more guesses");

        $this->sut->addGuess($game, $guess);
    }

    public function testAddWinningPegFinishTheGameAsWon()
    {
        $secretCode = ColorCode::createFromString('RGYB');
        $game = GameBuilder::create()->withColorCode($secretCode)->build();
        $guess = GuessBuilder::create($game)->withColorCode($secretCode)->build();

        $this->sut->addGuess($game, $guess);

        self::assertCount(1, $game->guesses());
        self::assertSame($guess->id(), $game->guesses()->first()->id());
        self::assertSame(GameStatus::Won, $game->status());
    }

    public function testAddFirstGuessStartTheGame()
    {
        $game = GameBuilder::create()->build();
        $guess = GuessBuilder::create($game)->build();

        $this->sut->addGuess($game, $guess);

        self::assertCount(1, $game->guesses());
        self::assertSame($guess->id(), $game->guesses()->first()->id());
        self::assertEquals(GameStatus::InProgress, $game->status());
    }

    public function testAddGameWithNoMatchesMarkedAsLost()
    {
        $secretCode = ColorCode::createFromString('BBBB');
        $colorCode = ColorCode::createFromString('YYYY');
        $game = GameBuilder::create()->withColorCode($secretCode)->build();
        $guess = GuessBuilder::create($game)->withColorCode($colorCode)->copy();
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());
        $game->addGuess($guess->build());

        $lastGuess = GuessBuilder::create($game)->withColorCode($colorCode)->build();

        $this->sut->addGuess($game, $lastGuess);

        self::assertCount(10, $game->guesses());
        self::assertSame(GameStatus::Lost, $game->status());
    }
}
