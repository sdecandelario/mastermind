<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Game\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateGameControllerTest extends WebTestCase
{
    public function testGameIsCreated()
    {
        $client = self::createClient();

        $client->request('POST', '/api/game');

        self::assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}
