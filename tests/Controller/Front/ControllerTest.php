<?php

namespace Blog\Test\Controller\Front;

use Blog\Core\FrontController;
use Blog\Test\Utils\ContainerTrait;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class ControllerTest extends TestCase
{
    use ContainerTrait;

    #[DataProvider("getRoutes")]
    public function testResponseForRouteInFrontend(string $path, string $method = 'GET', int $statusCode = 200): void
    {
        /** @var ContainerInterface */
        $container = $this->getContainer();

        /** @var FrontController */
        $app = $container->get('app.front_controller');

        $request = new ServerRequest($method, $path);

        /** @var ResponseInterface */
        $response = $app->start($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    /**
     * getRoutes
     *
     * @return array<mixed>
     */
    public static function getRoutes(): array
    {
        return [
            ['path' => '/'],
            ['path' => '/articles/post'],
            ['path' => '/connexion'],
            ['path' => '/inscription'],
            ['path' => '/demande-modification-mot-de-passe'],
            ['path' => '/modification-mot-de-passe'],
            ['path' => '/modification-mot', 'statusCode' => 404],
        ];
    }
}
