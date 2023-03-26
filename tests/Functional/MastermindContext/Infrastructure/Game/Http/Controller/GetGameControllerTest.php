<?php

declare(strict_types=1);

namespace Functional\MastermindContext\Infrastructure\Game\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetGameControllerTest extends WebTestCase
{
    public function testGameRetrieved()
    {
        $client = self::createClient();

        $client->request('GET', '/api/game/anId');

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
