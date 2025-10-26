<?php

declare(strict_types=1);

namespace Tests\Framework\Http\Request\Guzzle;

use PHPUnit\Framework\TestCase;
use Framework\Http\Interface\RequestInterface;
use Framework\Http\Request\Guzzle\GuzzleRequestFactory;

class GuzzleRequestFactoryTest extends TestCase
{
    public function testReturnGuzzleRequestInterface(): void
    {
        $guzzleRendererFactory = new GuzzleRequestFactory();
        $guzzleInterface = $guzzleRendererFactory->factory();

        $this->assertInstanceOf(RequestInterface::class, $guzzleInterface);
    }
}
