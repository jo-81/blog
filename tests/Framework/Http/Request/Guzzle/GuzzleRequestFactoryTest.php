<?php

declare(strict_types=1);

namespace Tests\Framework\Http\Request\Guzzle;

use PHPUnit\Framework\TestCase;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Request\Guzzle\GuzzleRequestFactory;

class GuzzleRequestFactoryTest extends TestCase
{
    public function testReturnGuzzleRequestInterface(): void
    {
        $guzzleRendererFactory = new GuzzleRequestFactory();

        $this->assertInstanceOf(AppRequestInterface::class, $guzzleRendererFactory->createFromGlobals());
    }
}
