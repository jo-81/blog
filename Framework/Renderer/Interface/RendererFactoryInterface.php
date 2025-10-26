<?php

declare(strict_types=1);

namespace Framework\Renderer\Interface;

interface RendererFactoryInterface
{
    /**
     * factory
     *
     * @param  string[] $paths
     * @param  mixed[] $options
     * @param  mixed[] $extensions
     * @return RendererInterface
     */
    public function factory(array $paths, array $options, array $extensions): RendererInterface;
}
