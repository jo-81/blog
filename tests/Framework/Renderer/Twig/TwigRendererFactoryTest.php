<?php

declare(strict_types=1);

namespace Tests\Framework\Renderer\Twig;

use PHPUnit\Framework\TestCase;
use Framework\Renderer\Twig\TwigRendererFactory;
use Framework\Renderer\Interface\RendererInterface;

class TwigRendererFactoryTest extends TestCase
{
    public function testReturnTwigRendererInterface(): void
    {
        $twigRendererFactory = new TwigRendererFactory();
        $rendererInterface = $twigRendererFactory->factory([], [], []);

        $this->assertInstanceOf(RendererInterface::class, $rendererInterface);
    }
}
