<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionEventListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof DomainException) {
            return;
        }

        $statusCode = $this->getStatusCode($exception);
        $response = new JsonResponse([
            'error' => $exception->getMessage(),
        ], $statusCode);

        $event->setResponse($response);
    }

    private function getStatusCode(DomainException $exception): int
    {
        $exceptionClass = get_class($exception);

        return match ($exceptionClass) {
            NotFoundException::class => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_BAD_REQUEST
        };
    }
}
