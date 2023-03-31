<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateGameTest extends WebTestCase
{
    public function testGameNotCreatedWithInvalidColorCodeLengthReturnBadRequest()
    {
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/game', [
            'colorCode' => '1',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['[colorCode]' => 'This value should have exactly 4 characters.'], $jsonResponse);
    }

    public function testGameNotCreatedWithInvalidColorCodeValuesReturnBadRequest()
    {
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/game', [
            'colorCode' => '1111',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['error' => 'Invalid combination, the allowed values accepted are (R, Y, G, B, W, O)'], $jsonResponse);
    }

    public function testGameIsCreatedWithRandomColorCode()
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
