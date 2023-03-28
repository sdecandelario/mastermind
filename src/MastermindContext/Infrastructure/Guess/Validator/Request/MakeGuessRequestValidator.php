<?php

declare(strict_types=1);

namespace App\MastermindContext\Infrastructure\Guess\Validator\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MakeGuessRequestValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(array $parameters): array
    {
        $constraint = new Assert\Collection([
            'colorCode' => new Assert\Required([
                new Assert\Length(4),
            ]),
        ]);

        $violations = $this->validator->validate($parameters, $constraint);

        $violationList = [];

        foreach ($violations as $violation) {
            $violationList[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $violationList;
    }
}
