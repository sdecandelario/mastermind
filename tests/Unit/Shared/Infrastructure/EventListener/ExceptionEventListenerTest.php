<?php

namespace App\Tests\Unit\Shared\Infrastructure\EventListener;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Infrastructure\EventListener\ExceptionEventListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ExceptionEventListenerTest extends TestCase
{
    private KernelInterface|MockObject $kernel;
    private Request|MockObject $request;
    private ExceptionEventListener $sut;

    protected function setUp(): void
    {
        $this->kernel = $this->createMock(KernelInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->sut = new ExceptionEventListener();
    }

    public function testNotDomainExceptionDoNothing()
    {
        $exception = new \Exception();
        $event = new ExceptionEvent($this->kernel, $this->request, 1, $exception);

        $this->sut->__invoke($event);

        self::assertNull($event->getResponse());
    }

    public function validExceptions(): array
    {
        $envelope = new Envelope(new \stdClass());
        $domainException = new class ('error message') extends DomainException {
        };
        $notFoundException = new class ('error message') extends NotFoundException {
        };

        return [
            [new HandlerFailedException($envelope, [$domainException]), Response::HTTP_BAD_REQUEST],
            [new HandlerFailedException($envelope, [$notFoundException]), Response::HTTP_NOT_FOUND],
            [$domainException, Response::HTTP_BAD_REQUEST],
            [$notFoundException, Response::HTTP_NOT_FOUND],
        ];
    }

    /**
     * @dataProvider validExceptions
     */
    public function testDomainExceptionSetStatusCode(DomainException|HandlerFailedException $exception, int $statusCode)
    {
        $event = new ExceptionEvent($this->kernel, $this->request, 1, $exception);

        $this->sut->__invoke($event);

        $response = $event->getResponse();

        self::assertNotNull($response);
        self::assertSame('{"error":"error message"}', $response->getContent());
        self::assertSame($statusCode, $response->getStatusCode());
    }
}
