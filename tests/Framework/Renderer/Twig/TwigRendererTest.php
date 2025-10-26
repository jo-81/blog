<?php

declare(strict_types=1);

namespace Tests\Framework\Renderer\Twig;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use Twig\Loader\FilesystemLoader;
use Framework\Renderer\Twig\TwigRenderer;
use Framework\Renderer\Exception\RendererException;

class TwigRendererTest extends TestCase
{
    private TwigRenderer $twigRenderer;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader([\dirname(__DIR__, 3) . "/Fixtures/Module/templates"]);
        $twig = new Environment($loader);

        $this->twigRenderer = new TwigRenderer($twig);
    }

    /**
     * testRenderWithTemplateNotExist
     *
     * @return void
     */
    public function testRenderWithTemplateNotExist(): void
    {
        $this->assertFalse($this->twigRenderer->isTemplateExists("template-not-exists.html.twig"));
        $this->expectException(RendererException::class);

        $this->twigRenderer->render("template-not-exists.html.twig");
    }

    /**
     * testRendererWithTemplateExist
     *
     * @return void
     */
    public function testRendererWithTemplateExist(): void
    {
        $this->assertEquals("<p>Template du module de test.</p>", $this->twigRenderer->render("base.html.twig"));
    }
}
