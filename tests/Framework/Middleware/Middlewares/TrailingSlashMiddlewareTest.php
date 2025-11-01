<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware\Middlewares;

use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Framework\Http\Request\Guzzle\GuzzleRequest;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Http\Response\Guzzle\GuzzleResponseFactory;
use Framework\Middleware\Middlewares\TrailingSlashMiddleware;

class TrailingSlashMiddlewareTest extends TestCase
{
    private TrailingSlashMiddleware $middleware;

    protected function setUp(): void
    {
        $responseFactory = new GuzzleResponseFactory();
        $this->middleware = new TrailingSlashMiddleware($responseFactory);
    }

    /**
     * testUrl
     *
     * @return void
     */
    #[DataProvider('provideUrls')]
    public function testUrls(string $path, int $statusCode): void
    {
        $request = new GuzzleRequest("GET", $path);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($this->getResponse($statusCode));

        $response = $this->middleware->process($request, $handler);

        $this->assertEquals($statusCode, $response->getStatusCode());
        if ($statusCode == 301) {
            $this->assertTrue($response->hasHeader('Location'));
        }
    }

    /**
     * provideUrls
     *
     * @return mixed[]
     */
    public static function provideUrls(): array
    {
        return [
            ['https://example.com/', 200],
            ['https://example.com/image.png', 200],
            ['https://example.com/articles/', 301],
            ['https://example.com/articles', 200],
        ];
    }

    private function getResponse(int $statusCode = 200): AppResponseInterface
    {
        $response = $this->createMock(AppResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('withStatus')->willReturn($response);

        return $response;
    }
}
