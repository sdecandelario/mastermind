<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Guess\Http;

use App\MastermindContext\Domain\ColorCode\ColorCode;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\MastermindContext\Domain\Game\GameStatus;
use App\MastermindContext\Domain\Guess\Guess;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\GuessBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

final class MakeGuessTest extends WebTestCase
{
    private KernelBrowser $client;
    private ContainerInterface $container;
    private GameRepositoryInterface $gameRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->gameRepository = self::getContainer()->get(GameRepositoryInterface::class);
    }

    public function testGuessWithoutValidColorCodeReturnBadRequest()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess");

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['[colorCode]' => 'This field is missing.'], $jsonResponse);
    }

    public function testGuessWithInvalidColorCodeLengthReturnBadRequest()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => '1',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['[colorCode]' => 'This value should have exactly 4 characters.'], $jsonResponse);
    }

    public function testInvalidGameId()
    {
        $this->client->jsonRequest('POST', '/api/game/anId/guess', [
            'colorCode' => '1234',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['error' => "The id 'anId' is not a valid uuid"], $jsonResponse);
    }

    public function testGameNotFound()
    {
        $gameId = GameId::create();

        $this->client->jsonRequest('POST', "/api/game/$gameId/guess", [
            'colorCode' => '1234',
        ]);

        self::assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['error' => "Game with id {$gameId->id()->__toString()} not found"], $jsonResponse);
    }

    public function testGuessWithInvalidColorCodeCombinationReturnBadRequest()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => 'ABCD',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['error' => 'The combination is wrong, the only values accepted are (R, Y, G and B)'], $jsonResponse);
    }

    public function testFirstGuessStartTheGame()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);
        $colorCode = ColorCode::random();

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => $colorCode->value(),
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $jsonResponse);

        $entityManager = $this->container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $savedGame = $this->gameRepository->findById($game->id());

        /**
         * @var Guess $guess
         */
        $guess = $entityManager->getRepository(Guess::class)->find($jsonResponse['id']);

        self::assertTrue($savedGame->isInProgress());
        self::assertCount(1, $savedGame->guesses());
        self::assertSame($colorCode->value(), $guess->colorCode()->value());
    }

    public function testMakeAGuessWithOneBlackPeg()
    {
        $colorCode = ColorCode::createFromString('RYGB');
        $game = GameBuilder::create()->withColorCode($colorCode)->build();
        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => 'RRRR',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $jsonResponse);

        $entityManager = $this->container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $savedGame = $this->gameRepository->findById($game->id());

        /**
         * @var Guess $guess
         */
        $guess = $entityManager->getRepository(Guess::class)->find($jsonResponse['id']);

        self::assertTrue($savedGame->isInProgress());
        self::assertCount(1, $savedGame->guesses());
        self::assertSame(1, $guess->blackPeg());
    }

    public function testMakeAGuessWithOneWhitePeg()
    {
        $colorCode = ColorCode::createFromString('RYGB');
        $game = GameBuilder::create()->withColorCode($colorCode)->build();
        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => 'RRRR',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $jsonResponse);

        $entityManager = $this->container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $savedGame = $this->gameRepository->findById($game->id());

        /**
         * @var Guess $guess
         */
        $guess = $entityManager->getRepository(Guess::class)->find($jsonResponse['id']);

        self::assertTrue($savedGame->isInProgress());
        self::assertCount(1, $savedGame->guesses());
        self::assertSame(1, $guess->whitePeg());
    }

    public function testMakeAWinnerGuessEndsTheGame()
    {
        $colorCode = ColorCode::createFromString('RYGB');
        $game = GameBuilder::create()->withColorCode($colorCode)->build();
        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => 'RYGB',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $jsonResponse);

        $entityManager = $this->container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $savedGame = $this->gameRepository->findById($game->id());

        /**
         * @var Guess $guess
         */
        $guess = $entityManager->getRepository(Guess::class)->find($jsonResponse['id']);

        self::assertTrue($savedGame->isWinner());
        self::assertCount(1, $savedGame->guesses());
        self::assertSame(4, $guess->blackPeg());
    }

    public function testAddingLastGuessWithNoMatchesMarkTheGameAsLost()
    {
        $colorCode = ColorCode::createFromString('BBBB');
        $game = GameBuilder::create()
            ->withColorCode($colorCode)
            ->build();

        $guess = GuessBuilder::create($game)->withColorCode(ColorCode::createFromString('RRRR'));

        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());
        $game->addGuess($guess->copy()->build());

        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => 'RRRR',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $jsonResponse);

        $entityManager = $this->container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $savedGame = $this->gameRepository->findById($game->id());

        /**
         * @var Guess $guess
         */
        $guess = $entityManager->getRepository(Guess::class)->find($jsonResponse['id']);

        self::assertTrue($savedGame->isLost());
        self::assertCount(10, $savedGame->guesses());
        self::assertSame(0, $guess->blackPeg());
    }

    public function finishedGameStatusProvider(): array
    {
        return [
            [GameStatus::Won],
            [GameStatus::Lost],
        ];
    }

    /**
     * @dataProvider finishedGameStatusProvider
     */
    public function testAddingLastGuessOnAFinishedGameReturnBadRequestError(GameStatus $status)
    {
        $game = GameBuilder::create()
            ->withStatus($status)
            ->build();

        $this->gameRepository->save($game);

        $this->client->jsonRequest('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => 'RRRR',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['error' => "The game {$game->id()->__toString()} is finished, not allow more guesses"], $jsonResponse);
    }
}
