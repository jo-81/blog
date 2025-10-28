<?php

declare(strict_types=1);

namespace Tests\Framework\Middlewares;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;
use Framework\Middlewares\MiddlewareRegistry;
use Framework\Middlewares\Exception\MiddlewareException;

class MiddlewareRegistryTest extends TestCase
{
    /**
     * testRegisterMiddleware
     *
     * @return void
     */
    public function testRegisterMiddleware(): void
    {
        $middleware = $this->getMiddleware();

        $middlewareRegistry = new MiddlewareRegistry();
        $middlewareRegistry->registerMiddleware($middleware);

        $this->assertCount(1, $middlewareRegistry->getMiddlewares());
        $this->assertTrue($middlewareRegistry->hasMiddleware($middleware));
    }

    /**
     * testRegisterMiddlewareTwice
     *
     * @return void
     */
    public function testRegisterMiddlewareTwice(): void
    {
        $middleware = $this->getMiddleware();

        $this->expectException(MiddlewareException::class);
        $this->expectExceptionMessage("Le middleware " . get_class($middleware) . " est déjà présent.");

        $middlewareRegistry = new MiddlewareRegistry();
        $middlewareRegistry
            ->registerMiddleware($middleware)
            ->registerMiddleware($middleware)
        ;
    }

    public function testGetMiddleware(): void
    {
        $middlewareRegistry = new MiddlewareRegistry();
        $this->assertNull($middlewareRegistry->getMiddleware(0));

        $middleware = $this->getMiddleware();
        $middlewareRegistry->registerMiddleware($middleware);
        $this->assertInstanceOf(MiddlewareInterface::class, $middlewareRegistry->getMiddleware(0));
    }

    private function getMiddleware(): MiddlewareInterface
    {
        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->method('process')->willReturn(new Response());

        return $middleware;
    }
}
