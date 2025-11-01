<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware\Middlewares;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Request\Guzzle\GuzzleRequest;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Middleware\Middlewares\LoggingMiddleware;

class LoggingMiddlewareTest extends TestCase
{
    private LoggingMiddleware $middleware;
    private $logger; // @phpstan-ignore missingType.property
    private $handler; // @phpstan-ignore missingType.property

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
        $this->middleware = new LoggingMiddleware($this->logger);
    }

    /**
     * testLogsSuccessfulRequest
     *
     * @return void
     */
    public function testLogsSuccessfulRequest(): void
    {
        $request = new GuzzleRequest("GET", 'https://example.com/articles');

        $response = $this->createMock(AppResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('withStatus')->willReturn($response);

        $this->handler->method('handle')->willReturn($response);

        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with(
                LogLevel::INFO,
                $this->stringContains('GET /articles - 200'),
                $this->callback(function ($context) {
                    return $context['method'] === 'GET'
                        && $context['status'] === 200
                        && isset($context['duration_ms']);
                })
            );

        $this->middleware->process($request, $this->handler);
    }

    /**
     * testLogs404AsWarning
     *
     * @return void
     */
    public function testLogs404AsWarning(): void
    {
        $request = new GuzzleRequest("GET", 'https://example.com/articles');

        $response = $this->createMock(AppResponseInterface::class);
        $response->method('getStatusCode')->willReturn(404);
        $response->method('withStatus')->willReturn($response);

        $this->handler->method('handle')->willReturn($response);

        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::WARNING, $this->anything(), $this->anything());

        $this->middleware->process($request, $this->handler);
    }

    /**
     * testLogs500AsError
     *
     * @return void
     */
    public function testLogs500AsError(): void
    {
        $request = new GuzzleRequest("GET", 'https://example.com/articles');

        $response = $this->createMock(AppResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);
        $response->method('withStatus')->willReturn($response);

        $this->handler->method('handle')->willReturn($response);

        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::ERROR, $this->anything(), $this->anything());

        $this->middleware->process($request, $this->handler);
    }

    /**
     * testLogsExceptionAndRethrows
     *
     * @return void
     */
    public function testLogsExceptionAndRethrows(): void
    {
        $exception = new \RuntimeException('Test error');
        $request = new GuzzleRequest("GET", 'https://example.com/articles');
        $this->handler->method('handle')->willThrowException($exception);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with(
                $this->stringContains('Exception: Test error'),
                $this->callback(function ($context) {
                    return $context['exception'] === \RuntimeException::class
                        && $context['message'] === 'Test error'
                        && isset($context['file'])
                        && isset($context['line']);
                })
            );

        $this->expectException(\RuntimeException::class);

        $this->middleware->process($request, $this->handler);
    }
}
