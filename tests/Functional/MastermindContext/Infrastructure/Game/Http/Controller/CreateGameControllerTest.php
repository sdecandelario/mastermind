<?php

declare(strict_types=1);

namespace Functional\MastermindContext\Infrastructure\Game\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateGameControllerTest extends WebTestCase
{
    public function testGameIsCreated()
    {
        $client = self::createClient();

        $client->request('POST', '/api/game');
    }
}
