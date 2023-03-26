<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\MastermindContext\Domain\Game\GameId;
use App\Tests\Builder\GameBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class GameWithIdFixture extends Fixture
{
    public function __construct(private readonly GameId $id)
    {
    }

    public function load(ObjectManager $manager)
    {
        $game = GameBuilder::create()->withId($this->id)->build();
        $manager->persist($game);
        $manager->flush();
    }
}
