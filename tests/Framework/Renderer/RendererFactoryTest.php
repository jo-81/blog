<?php

declare(strict_types=1);

namespace Tests\Framework\Renderer;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Framework\Renderer\RendererFactory;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Renderer\Interface\RendererFactoryInterface;

class RendererFactoryTest extends TestCase
{
    private RendererFactory $rendererFactory;

    protected function setUp(): void
    {
        $this->rendererFactory = new RendererFactory();
    }

    /**
     * testInvokeReturnRendererInterface
     *
     * @return void
     */
    public function testInvokeReturnRendererInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $rendererFactory = $this->createMock(RendererFactoryInterface::class);
        $renderer = $this->rendererFactory->__invoke($container, $rendererFactory);

        $this->assertInstanceOf(RendererInterface::class, $renderer);
    }
}
