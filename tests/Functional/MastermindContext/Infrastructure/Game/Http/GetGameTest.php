<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\MastermindContext\Domain\Game\GameStatus;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\GuessBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetGameTest extends WebTestCase
{
    public function testInvalidGameId()
    {
        $client = self::createClient();

        $client->request('GET', '/api/game/anId');

        self::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['error' => "The id 'anId' is not a valid game id"], $jsonResponse);
    }

    public function testGameNotFound()
    {
        $client = self::createClient();
        $gameId = GameId::create();

        $client->request('GET', "/api/game/$gameId");

        self::assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['error' => "Game with id {$gameId->id()->__toString()} not found"], $jsonResponse);
    }

    public function testGameRetrieved()
    {
        $client = self::createClient();
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $id = GameId::create();
        $game = GameBuilder::create()->withId($id)->build();
        $entityManager->persist($game);
        $entityManager->flush();
        $entityManager->refresh($game);

        $guess = GuessBuilder::create($game)->build();
        $entityManager->persist($guess);
        $entityManager->flush();

        $client->request('GET', "/api/game/$id");

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertArrayHasKey('status', $jsonResponse);

        /**
         * @var GameRepositoryInterface $gameRepository
         */
        $gameRepository = self::getContainer()->get(GameRepositoryInterface::class);
        $game = $gameRepository->findById($id);

        self::assertSame([
            'id' => $game->id()->__toString(),
            'created' => $game->created()->format('Y-m-d H:i:s'),
            'status' => $game->status()->value,
            'guesses' => [
                [
                    'id' => $guess->id()->__toString(),
                    'created' => $guess->created()->format('Y-m-d H:i:s'),
                    'colorCode' => $guess->colorCode()->value(),
                    'blackPeg' => $guess->blackPeg(),
                    'whitePeg' => $guess->whitePeg(),
                ],
            ],
        ], $jsonResponse);
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
    public function testGameFinishedRetrievedShowTheSecretCode(GameStatus $status): void
    {
        $client = self::createClient();
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $id = GameId::create();
        $game = GameBuilder::create()
            ->withId($id)
            ->withStatus($status)
            ->build();

        $entityManager->persist($game);
        $entityManager->flush();

        $client->request('GET', "/api/game/$id");

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertArrayHasKey('status', $jsonResponse);

        /**
         * @var GameRepositoryInterface $gameRepository
         */
        $gameRepository = self::getContainer()->get(GameRepositoryInterface::class);
        $game = $gameRepository->findById($id);

        self::assertSame([
            'id' => $game->id()->value(),
            'created' => $game->created()->format('Y-m-d H:i:s'),
            'status' => $game->status()->value,
            'secretCode' => $game->secretCode()->value(),
            'guesses' => [],
        ], $jsonResponse);
    }
}
