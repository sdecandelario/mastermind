<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class ExceptionEventListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

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
        $statusCode = Response::HTTP_BAD_REQUEST;

        if ($exception instanceof NotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        return $statusCode;
    }
}
