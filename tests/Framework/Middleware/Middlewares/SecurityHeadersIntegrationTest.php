<?php

declare(strict_types=1);

namespace Tests\Framework\Middleware\Middlewares;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Response\Guzzle\GuzzleResponse;
use Framework\Middleware\Middlewares\SecurityHeadersMiddleware;

class SecurityHeadersIntegrationTest extends TestCase
{
    /**
     * testProductionCspIsStrict
     *
     * @return void
     */
    public function testProductionCspIsStrict(): void
    {
        $middleware = new SecurityHeadersMiddleware(isProduction: true);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn(new GuzzleResponse());
        $request = new ServerRequest("GET", 'https://example.com/admin/articles');

        $response = $middleware->process($request, $handler);
        $csp = $response->getHeaderLine('Content-Security-Policy');

        $this->assertStringContainsString("script-src 'self'", $csp);
        $this->assertStringNotContainsString('unsafe-inline', $csp);
        $this->assertStringNotContainsString('unsafe-eval', $csp);
        $this->assertFalse($response->hasHeader('X-Environment'));
    }

    /**
     * testDevelopmentCspIsPermissive
     *
     * @return void
     */
    public function testDevelopmentCspIsPermissive(): void
    {
        $middleware = new SecurityHeadersMiddleware(isProduction: false);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn(new GuzzleResponse());
        $request = new ServerRequest("GET", 'https://example.com/admin/articles');

        $response = $middleware->process($request, $handler);
        $csp = $response->getHeaderLine('Content-Security-Policy');

        $this->assertStringContainsString('unsafe-inline', $csp);
        $this->assertStringContainsString('unsafe-eval', $csp);
        $this->assertTrue($response->hasHeader('X-Environment'));
        $this->assertEquals('development', $response->getHeaderLine('X-Environment'));
    }
}
