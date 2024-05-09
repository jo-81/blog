<?php

namespace Framework;

use App\Tests\Traits\ContainerTrait;
use Framework\Middleware\Exception\MiddlewareNotFoundException;
use Framework\Middleware\MiddlewareHandler;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class ApplicationTest extends TestCase
{
    use ContainerTrait;

    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->container = $this->getContainer(dirname(__DIR__).'/Fixtures/config');
    }

    public function testApplicationWithoutMiddlewares(): void
    {
        $this->expectException(MiddlewareNotFoundException::class);

        $middlewareHandler = new MiddlewareHandler();
        $application = new Application();
        $application->setMiddlewareHandler($middlewareHandler);

        $request = new ServerRequest('GET', '/');

        $application->run($request);
    }

    #[DataProvider('getDataForRouteWithResponse')]
    public function testResponseWithMiddlewares(string $method, string $path): void
    {
        /** @var Application */
        $application = $this->container->get('app');
        $request = new ServerRequest($method, $path);
        $response = $application->run($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * getDataForRouteWithResponse.
     *
     * @return array<int, array<string, string>>
     */
    public static function getDataForRouteWithResponse(): array
    {
        return [
            ['method' => 'GET', 'path' => '/'],
            ['method' => 'GET', 'path' => '/posts'],
            ['method' => 'GET', 'path' => '/posts/'],
            ['method' => 'GET', 'path' => '/posts/1'],
        ];
    }
}
