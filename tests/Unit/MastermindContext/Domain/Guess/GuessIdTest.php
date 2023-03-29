<?php

namespace App\Tests\Unit\MastermindContext\Domain\Guess;

use App\MastermindContext\Domain\Guess\Exception\InvalidGuessIdException;
use App\MastermindContext\Domain\Guess\GuessId;
use PHPUnit\Framework\TestCase;

class GuessIdTest extends TestCase
{
    public function testCreateFromStringWithInvalidFormadThrowException(): void
    {
        $this->expectException(InvalidGuessIdException::class);
        $this->expectExceptionMessage('The id \'invalid\' is not a valid guess id');

        GuessId::createFromString('invalid');
    }

    public function testCreateFromValidString(): void
    {
        $id = GuessId::create();

        $guessId = GuessId::createFromString($id->value());

        self::assertEquals($id, $guessId);
    }
}
