<?php

declare(strict_types=1);

namespace App\Tests\Functional\MastermindContext\Infrastructure\Guess\Http;

use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use App\Tests\Builder\GameBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

final class MakeGuessTest extends WebTestCase
{
    private KernelBrowser $client;
    private ContainerInterface $container;
    private GameRepositoryInterface $gameRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->gameRepository = self::getContainer()->get(GameRepositoryInterface::class);
    }

    public function testGuessWithoutValidColorCodeReturnBadRequest()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);

        $this->client->request('POST', "/api/game/{$game->id()->__toString()}/guess");

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['[colorCode]' => 'This field is missing.'], $jsonResponse);
    }

    public function testGuessWithInvalidColorCodeLengthReturnBadRequest()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);

        $this->client->request('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => '1',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['[colorCode]' => 'This value should have exactly 4 characters.'], $jsonResponse);
    }

    public function testInvalidGameId()
    {
        $this->client->request('POST', '/api/game/anId/guess', [
            'colorCode' => '1234',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['error' => "The id 'anId' is not a valid uuid"], $jsonResponse);
    }

    public function testGameNotFound()
    {
        $gameId = GameId::create();

        $this->client->request('POST', "/api/game/$gameId/guess", [
            'colorCode' => '1234',
        ]);

        self::assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame(['error' => "Game with id {$gameId->id()->__toString()} not found"], $jsonResponse);
    }

    public function testFirstGuessStartTheGame()
    {
        $game = GameBuilder::create()->build();
        $this->gameRepository->save($game);

        $this->client->request('POST', "/api/game/{$game->id()->__toString()}/guess", [
            'colorCode' => '1234',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $jsonResponse);

        $entityManager = $this->container->get(EntityManagerInterface::class);

        $entityManager->clear();

        $savedGame = $this->gameRepository->findById($game->id());

        self::assertTrue($savedGame->isInProgress());
        self::assertCount(1, $savedGame->guesses());
    }
}
