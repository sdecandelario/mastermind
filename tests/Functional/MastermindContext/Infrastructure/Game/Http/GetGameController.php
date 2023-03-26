<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\Tests\Fixture\GameWithIdFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetGameController extends WebTestCase
{
    public function testInvalidGameId()
    {
        $client = self::createClient();

        $client->request('GET', '/api/game/anId');

        self::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['error' => "The id 'anId' is not a valid uuid"], $jsonResponse);
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
        (new GameWithIdFixture($id))->load($entityManager);

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
            'status' => $game->status()->value,
        ], $jsonResponse);
    }
}
