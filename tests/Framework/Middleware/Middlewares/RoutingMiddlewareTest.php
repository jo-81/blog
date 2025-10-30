<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware\Middlewares;

use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Router\Interface\RouteInterface;
use Framework\Router\Interface\RouterInterface;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Router\Exception\RouteNotFoundException;
use Framework\Middleware\Middlewares\RoutingMiddleware;

class RoutingMiddlewareTest extends TestCase
{
    /**
     * testNotRouteRegistered
     *
     * @return void
     */
    public function testNotRouteRegistered(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $router->method('match')->willReturn(null);

        $request = $this->createMock(AppRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $middleware = new RoutingMiddleware($router);

        $this->expectException(RouteNotFoundException::class);
        $middleware->process($request, $handler);
    }

    /**
     * testWithRouteExists
     *
     * @return void
     */
    public function testWithRouteExists(): void
    {
        $route = $this->createMock(RouteInterface::class);

        $router = $this->createMock(RouterInterface::class);
        $router->method('match')->willReturn($route);

        $request = $this->createMock(AppRequestInterface::class);
        $request->method('withAttribute')
            ->with(RoutingMiddleware::ATTRIBUTE_NAME, $route)
            ->willReturnSelf()
        ;

        $response = $this->createMock(AppResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        $middleware = new RoutingMiddleware($router);

        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }
}
