<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware\Middlewares;

use Framework\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Router\Interface\RouteInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Framework\Http\Request\Guzzle\GuzzleRequest;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Router\Exception\RouteNotFoundException;
use Framework\Middleware\Middlewares\RoutingMiddleware;
use Framework\Middleware\Exception\RouteCallbackValidatorException;
use Framework\Middleware\Middlewares\RouteCallbackValidatorMiddleware;

class RouteCallbackValidatorMiddlewareTest extends TestCase
{
    private RouteCallbackValidatorMiddleware $middleware;

    protected function setUp(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $this->middleware = new RouteCallbackValidatorMiddleware($container);
    }

    /**
     * testWithRouteNotExist
     *
     * @return void
     */
    public function testWithRouteNotExist(): void
    {
        $request = $this->createMock(AppRequestInterface::class);
        $request->method('withAttribute')
            ->with(RoutingMiddleware::ATTRIBUTE_NAME, null)
            ->willReturnSelf()
        ;

        $this->expectException(RouteNotFoundException::class);

        $this->middleware->process($request, $this->getHandler());
    }

    #[DataProvider('provideDataRoute')]
    public function testWithRouteExistWithBadTarget(RouteInterface $route): void
    {
        $request = new GuzzleRequest("GET", "/");
        $request = $request->withAttribute(RoutingMiddleware::ATTRIBUTE_NAME, $route);

        $this->expectException(RouteCallbackValidatorException::class);

        $this->middleware->process($request, $this->getHandler());
    }

    /**
     * @return array<RouteInterface[]>
     */
    public static function provideDataRoute(): array
    {
        return [
            [new Route('homepage', '/')],
            [new Route('homepage', '/', 'callbackNotValid')],
            [new Route('homepage', '/', 'callbackNotValid@')],
        ];
    }

    private function getHandler(): RequestHandlerInterface
    {
        $response = $this->createMock(AppResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        return $handler;
    }
}
