<?php

namespace App\MastermindContext\Domain\Game;

interface GameRepositoryInterface
{
    public function findById(GameId $id): Game;
}
