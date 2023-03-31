<?php

namespace App\Tests\Unit\MastermindContext\Infrastructure\Game\Validator\Request;

use App\MastermindContext\Infrastructure\Game\Validator\Request\CreateGameRequestValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateGameRequestValidatorTest extends TestCase
{
    private ValidatorInterface|MockObject $validator;
    private Assert\Collection $constraint;
    private CreateGameRequestValidator $sut;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->constraint = new Assert\Collection([
            'colorCode' => new Assert\Optional([
                new Assert\Length(4),
            ]),
        ]);

        $this->sut = new CreateGameRequestValidator(
            $this->validator
        );
    }

    public function testValidationFailsReturnArrayOfErrors()
    {
        $constraintViolation = $this->createMock(ConstraintViolationInterface::class);
        $constraintViolationList = new ConstraintViolationList([$constraintViolation]);

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with([], $this->constraint)
            ->willReturn($constraintViolationList);

        $constraintViolation
            ->expects(self::once())
            ->method('getPropertyPath')
            ->willReturn('property');

        $constraintViolation
            ->expects(self::once())
            ->method('getMessage')
            ->willReturn('message');

        $result = $this->sut->validate([]);

        self::assertSame(['property' => 'message'], $result);
    }

    public function testValidationWithoutErrors()
    {
        $constraintViolationList = $this->createMock(ConstraintViolationListInterface::class);

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with([], $this->constraint)
            ->willReturn($constraintViolationList);

        $result = $this->sut->validate([]);

        self::assertSame([], $result);
    }
}
