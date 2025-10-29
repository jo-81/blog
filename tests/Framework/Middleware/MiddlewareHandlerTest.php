<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware;

use PHPUnit\Framework\TestCase;
use Framework\Middleware\MiddlewareHandler;
use Framework\Middleware\MiddlewareRegistry;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\Fixtures\Middleware\ExampleMiddleware;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Middleware\Exception\MiddlewareException;

class MiddlewareHandlerTest extends TestCase
{
    private MiddlewareRegistry $middlewareRegistry;

    protected function setUp(): void
    {
        $this->middlewareRegistry = new MiddlewareRegistry();
    }

    /**
     * testHandleWhenFinalHandlerNotRegistered
     *
     * @return void
     */
    public function testHandleWhenFinalHandlerNotRegistered(): void
    {
        $this->expectException(MiddlewareException::class);
        $this->expectExceptionMessage("Aucun final handler n'a été défini.");

        $request = $this->createMock(ServerRequestInterface::class);
        $middlewareHandler = new MiddlewareHandler($this->middlewareRegistry);
        $middlewareHandler->handle($request);
    }

    /**
     * testHandleWhenNotMiddlewareRegistered
     *
     * @return void
     */
    public function testHandleWhenNotMiddlewareRegistered(): void
    {
        $responseInterface = $this->createMock(AppResponseInterface::class);
        $finalHandler = $this->createMock(RequestHandlerInterface::class);
        $finalHandler->method('handle')->willReturn($responseInterface);

        $request = $this->createMock(ServerRequestInterface::class);
        $middlewareHandler = new MiddlewareHandler($this->middlewareRegistry);
        $middlewareHandler->setFinalHandler($finalHandler);
        $response = $middlewareHandler->handle($request);

        $this->assertInstanceOf(AppResponseInterface::class, $response);
    }

    /**
     * testHadle
     *
     * @return void
     */
    public function testHadle(): void
    {
        $responseInterface = $this->createMock(AppResponseInterface::class);
        $finalHandler = $this->createMock(RequestHandlerInterface::class);
        $finalHandler->method('handle')->willReturn($responseInterface);

        $this->middlewareRegistry->registerMiddleware(new ExampleMiddleware());

        $request = $this->createMock(ServerRequestInterface::class);
        $middlewareHandler = new MiddlewareHandler($this->middlewareRegistry);
        $middlewareHandler->setFinalHandler($finalHandler);
        $response = $middlewareHandler->handle($request);

        $this->assertInstanceOf(AppResponseInterface::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
