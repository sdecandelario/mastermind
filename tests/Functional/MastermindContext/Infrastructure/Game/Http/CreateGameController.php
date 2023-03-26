<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateGameController extends WebTestCase
{
    public function testGameIsCreated()
    {
        $client = self::createClient();
        $container = self::getContainer();

        $client->request('POST', '/api/game');

        $response = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        self::assertArrayHasKey('id', $response);
        self::assertTrue(Uuid::isValid($response['id']));

        /**
         * @var GameRepositoryInterface $gameRepository
         */
        $gameRepository = $container->get(GameRepositoryInterface::class);
        $entityManager = $container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $game = $gameRepository->findById(GameId::createFromString($response['id']));

        self::assertSame($response['id'], $game->id()->id()->toRfc4122());
        self::assertTrue($game->isStarted());
        self::assertTrue(4 === mb_strlen($game->secretCode()->value()));
    }
}
