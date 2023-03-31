<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Game\Validator\Request;

use App\Shared\Infrastructure\Validator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class CreateGameRequestValidator extends AbstractRequestValidator
{
    protected function getConstraints(): Constraint
    {
        return new Assert\Collection([
            'colorCode' => new Assert\Optional([
                new Assert\Length(4),
            ]),
        ]);
    }
}
