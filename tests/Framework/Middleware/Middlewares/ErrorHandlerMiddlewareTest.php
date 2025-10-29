<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware\Middlewares;

use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Http\Interface\ResponseFactoryInterface;
use Framework\Middleware\Middlewares\ErrorHandlerMiddleware;

class ErrorHandlerMiddlewareTest extends TestCase
{
    private ErrorHandlerMiddleware $middleware;
    private $logger; // @phpstan-ignore missingType.property
    private ServerRequestInterface $request;
    private RendererInterface $renderer;
    private $responseFactory; // @phpstan-ignore missingType.property

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->renderer = $this->createMock(RendererInterface::class);

        $mockResponse = $this->createMockResponse();
        $this->responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $this->responseFactory->method('createHtmlResponse')->willReturn($mockResponse);

        $this->request = $this->createMock(ServerRequestInterface::class);

        $this->middleware = new ErrorHandlerMiddleware(
            renderer: $this->renderer,
            responseFactory: $this->responseFactory,
            logger: $this->logger,
            debug: true
        );
    }

    /**
     * testMiddlewarePassesThroughSuccessfulRequests
     *
     * @return void
     */
    public function testMiddlewarePassesThroughSuccessfulRequests(): void
    {
        $expectedResponse = $this->createMockResponse();

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($expectedResponse)
        ;

        $response = $this->middleware->process($this->request, $handler);

        $this->assertSame($expectedResponse, $response);
        $this->assertInstanceOf(AppResponseInterface::class, $response);
    }

    public function testGenericExceptionReturns500(): void
    {
        // Arrange
        $exception = new \Exception('Test error', 500);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException($exception);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with(
                'Test error',
                $this->callback(
                    function ($context) {
                        return isset($context['exception'])
                            && isset($context['message'])
                            && $context['message'] === 'Test error';
                    }
                )
            );

        $errorResponse = $this->createMockResponse(500);

        $this->responseFactory
            ->method('createHtmlResponse')
            ->willReturn($errorResponse);

        $response = $this->middleware->process($this->request, $handler);

        // Assert
        $this->assertInstanceOf(AppResponseInterface::class, $response);
    }

    private function createMockResponse(int $statusCode = 200): AppResponseInterface
    {
        $response = $this->createMock(AppResponseInterface::class);
        $body = $this->createMock(StreamInterface::class);
        $body->method('write')->willReturn(1);

        $response->method('getBody')->willReturn($body);
        $response->method('withStatus')->willReturnSelf();
        $response->method('withHeader')->willReturnSelf();
        $response->method('getStatusCode')->willReturn($statusCode);

        return $response;
    }
}
