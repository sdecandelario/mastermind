<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestValidator
{
    public function __construct(protected readonly ValidatorInterface $validator)
    {
    }

    abstract protected function getConstraints(): Constraint;

    public function validate(array $parameters): array
    {
        $violations = $this->validator->validate($parameters, $this->getConstraints());

        $violationList = [];

        foreach ($violations as $violation) {
            $violationList[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $violationList;
    }
}
