<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Guess\Validator\Request;

use App\Shared\Infrastructure\Validator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class MakeGuessRequestValidator extends AbstractRequestValidator
{
    protected function getConstraints(): Constraint
    {
        return new Assert\Collection([
            'colorCode' => new Assert\Required([
                new Assert\Length(4),
            ]),
        ]);
    }
}
