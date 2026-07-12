<?php

declare(strict_types=1);

namespace Framework\Renderer\TwigExtensions;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ViteAssetExtension extends AbstractExtension
{
    private string $manifestPath;

    public function __construct(
        private string $appEnv,
        string $publicPath,
        private string $viteServer,
    ) {
        $this->manifestPath = $publicPath . '/build/.vite/manifest.json';
    }

    /**
     * Enregistre les fonctions utilisables dans Twig
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'renderAsset'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Génère les balises HTML dynamiquement selon l'environnement
     */
    public function renderAsset(string $entry): string
    {
        if ($this->appEnv !== 'prod') {
            return $this->renderDevAssets($entry);
        }

        return $this->renderProdAssets($entry);
    }

    private function renderDevAssets(string $entry): string
    {
        // En Dev, on injecte le client de reload de Vite + le script d'entrée
        $html = sprintf('<script type="module" src="%s/@vite/client"></script>' . "\n", $this->viteServer);
        $html .= sprintf('<script type="module" src="%s/%s"></script>', $this->viteServer, $entry);

        return $html;
    }

    private function renderProdAssets(string $entry): string
    {
        if (!file_exists($this->manifestPath)) {
            throw new \RuntimeException(sprintf('Le fichier manifest.json est introuvable à l\'emplacement : %s. Pensez à lancer "npm run build".', $this->manifestPath));
        }

        $manifest = json_decode(file_get_contents($this->manifestPath), true);

        if (!isset($manifest[$entry])) {
            throw new \InvalidArgumentException(sprintf('L\'entrée "%s" n\'existe pas dans le fichier manifest.json.', $entry));
        }

        $html = '';
        $fileData = $manifest[$entry];

        // 1. Générer les balises CSS liées à cette entrée s'il y en a
        if (!empty($fileData['css'])) {
            foreach ($fileData['css'] as $cssFile) {
                $html .= sprintf('<link rel="stylesheet" href="/build/%s">' . "\n", $cssFile);
            }
        }

        // 2. Générer la balise JS principale
        $html .= sprintf('<script type="module" src="/build/%s"></script>', $fileData['file']);

        return $html;
    }
}
