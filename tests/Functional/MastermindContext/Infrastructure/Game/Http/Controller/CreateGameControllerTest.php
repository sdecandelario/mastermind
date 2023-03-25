<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http\Controller;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateGameControllerTest extends WebTestCase
{
    public function testGameIsCreated()
    {
        $client = self::createClient();
        $container = self::getContainer();

        $client->request('POST', '/api/game');

        var_dump(getenv('DATABASE_URL'));

        $response = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        self::assertArrayHasKey('id', $response);
        self::assertTrue(Uuid::isValid($response['id']));

        /**
         * @var GameRepositoryInterface $gameRepository
         */
        $gameRepository = $container->get(GameRepositoryInterface::class);

        $gameRepository->findById(GameId::createFromString($response['id']));
    }
}
