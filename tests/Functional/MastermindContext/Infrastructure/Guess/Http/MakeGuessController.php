<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Guess\Http;

use App\MastermindContext\Domain\Game\GameId;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MakeGuessController extends WebTestCase
{
    public function testInvalidGameId()
    {
        $client = self::createClient();

        $client->request('POST', '/api/game/anId/guess');

        self::assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['error' => "The id 'anId' is not a valid uuid"], $jsonResponse);
    }

    public function testGameNotFound()
    {
        $client = self::createClient();
        $gameId = GameId::create();

        $client->request('POST', "/api/game/$gameId/guess");

        self::assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(['error' => "Game with id {$gameId->id()->__toString()} not found"], $jsonResponse);
    }
}
