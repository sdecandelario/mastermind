<?php

declare(strict_types=1);

namespace App\MastermindContext\Domain\Game;

enum GameStatus: string
{
    case Started = 'Started';
    case InProgress = 'InProgress';
    case Finished = 'Finished';
}
