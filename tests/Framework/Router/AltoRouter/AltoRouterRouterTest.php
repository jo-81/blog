<?php

declare(strict_types=1);

namespace Tests\Framework\Router\AltoRouter;

use AltoRouter;
use Framework\Router\Route;
use PHPUnit\Framework\TestCase;
use Framework\Http\Interface\RequestInterface;
use Framework\Router\Interface\RouteInterface;
use Framework\Router\Exception\RouterException;
use Framework\Router\AltoRouter\AltoRouterRouter;

class AltoRouterRouterTest extends TestCase
{
    private AltoRouterRouter $router;

    protected function setUp(): void
    {
        $routes = [];

        $altoRouter = new AltoRouter($routes);
        $this->router = new AltoRouterRouter($altoRouter);
    }

    /**
     * testRegisterRoute
     *
     * @return void
     */
    public function testRegisterRoute(): void
    {
        $route = new Route('homepage', "/");
        $this->router->registerRoute($route);

        $this->assertArrayHasKey('homepage', $this->router->getRoutes());
        $this->assertEquals("/", $this->router->generate('homepage'));

        $this->expectException(RouterException::class);
        $this->expectExceptionMessage("La route posts n'existe pas.");
        $this->router->getRoute('posts');
    }

    /**
     * testGenerateRoute
     *
     * @return void
     */
    public function testGenerateRoute(): void
    {
        $this->router
            ->registerRoute(new Route('post.list', "/posts"))
            ->registerRoute(new Route('post.single', "/posts/[i:id]"))
        ;

        $this->assertEquals("/posts", $this->router->generate('post.list'));
        $this->assertEquals("/posts/1", $this->router->generate('post.single', ['id' => 1]));
    }

    /**
     * testMatchRoute
     *
     * @return void
     */
    public function testMatchRoute(): void
    {
        $this->router->registerRoute(new Route('post.list', "/posts"));

        $request = $this->createMock(RequestInterface::class);
        $request->method('getUri')->willReturn('/posts');
        $request->method('getMethod')->willReturn('GET');

        $route = $this->router->match($request);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }

    /**
     * testMatchRouteWithParameter
     *
     * @return void
     */
    public function testMatchRouteWithParameter(): void
    {
        $this->router->registerRoute(new Route('post.single', "/posts/[i:id]"));

        $request = $this->createMock(RequestInterface::class);
        $request->method('getUri')->willReturn('/posts/123');
        $request->method('getMethod')->willReturn('GET');

        $route = $this->router->match($request);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }

    /**
     * testMatchRouteNotFound
     *
     * @return void
     */
    public function testMatchRouteNotFound(): void
    {
        $this->router->registerRoute(new Route('post.list', "/posts"));

        $request = $this->createMock(RequestInterface::class);
        $request->method('getUri')->willReturn('/invalid');
        $request->method('getMethod')->willReturn('GET');

        $route = $this->router->match($request);

        $this->assertNull($route);
    }

    /**
     * testGenerateRouteNotExist
     *
     * @return void
     */
    public function testGenerateRouteNotExist(): void
    {
        $this->expectException(RouterException::class);
        $this->expectExceptionMessage("La route homepage n'existe pas.");

        $this->router->generate("homepage");
    }
}
