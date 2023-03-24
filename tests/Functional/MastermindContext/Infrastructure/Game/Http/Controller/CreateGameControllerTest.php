<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateGameControllerTest extends WebTestCase
{
    public function testGameIsCreated()
    {
        $client = self::createClient();

        $client->request('POST', '/api/game');

        $requestResponse = json_decode($client->getResponse()->getContent(), true);

        self::assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        self::assertArrayHasKey('id', $requestResponse);
        self::assertTrue(Uuid::isValid($requestResponse['id']));
    }
}
