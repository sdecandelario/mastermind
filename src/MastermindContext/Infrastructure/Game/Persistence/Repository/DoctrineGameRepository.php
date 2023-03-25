<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Persistence\Repository;

use App\MastermindContext\Domain\Game\Game;
use App\MastermindContext\Domain\Game\GameId;
use App\MastermindContext\Domain\Game\GameRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineGameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findById(GameId $id): Game
    {
        return $this->find($id->id()->toRfc4122());
    }
}
