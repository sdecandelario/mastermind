<?php

namespace App\Tests\Unit\MastermindContext\Application\Guess\Command;

use App\MastermindContext\Application\Guess\Command\MakeGuessCommand;
use App\MastermindContext\Application\Guess\Command\MakeGuessCommandHandler;
use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\Service\AddGuess;
use App\MastermindContext\Domain\Game\Service\GameFinder;
use App\MastermindContext\Domain\Guess\Guess;
use App\MastermindContext\Domain\Guess\GuessId;
use App\Tests\Builder\GameBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MakeGuessCommandHandlerTest extends TestCase
{
    private GameFinder|MockObject $gameFinder;
    private AddGuess|MockObject $addGuess;
    private MakeGuessCommandHandler $sut;

    protected function setUp(): void
    {
        $this->gameFinder = $this->createMock(GameFinder::class);
        $this->addGuess = $this->createMock(AddGuess::class);

        $this->sut = new MakeGuessCommandHandler(
            $this->gameFinder,
            $this->addGuess
        );
    }

    public function testMakeGuessSuccess(): void
    {
        $guessId = GuessId::create();
        $gameId = GameId::create();
        $game = GameBuilder::create()->withId($gameId)->build();
        $colorCode = ColorCode::random();

        $this->gameFinder
            ->expects(self::once())
            ->method('findOrFail')
            ->with($gameId)
            ->willReturn($game);

        $this->addGuess
            ->expects(self::once())
            ->method('addGuess')
            ->with(
                $game,
                self::callback(function (Guess $guess) use ($colorCode, $guessId) {
                    self::assertSame($guessId, $guess->id());
                    self::assertSame($colorCode->value(), $guess->colorCode()->value());
                    self::assertSame(0, $guess->blackPeg());
                    self::assertSame(0, $guess->whitePeg());

                    return true;
                })
            );

        $this->sut->__invoke(
            new MakeGuessCommand(
                $guessId,
                $gameId,
                $colorCode->value()
            )
        );
    }
}
