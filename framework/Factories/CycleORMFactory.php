<?php

declare(strict_types=1);

namespace Framework\Factories;

use Cycle\Schema;
use Cycle\ORM\ORM;
use Cycle\Annotated;
use Spiral\Tokenizer;
use Cycle\Database\Config;
use Cycle\Database\DatabaseManager;
use Cycle\ORM\Config\RelationConfig;
use Psr\Container\ContainerInterface;
use Cycle\ORM\Factory as CycleFactory;
use Cycle\Database\Config\DatabaseConfig;
use Framework\Adapters\CycleEntityManager;
use Framework\Database\EntityManagerInterface;
use Cycle\Annotated\Locator\TokenizerEntityLocator;
use Cycle\Annotated\Locator\TokenizerEmbeddingLocator;

class CycleORMFactory
{
    public function __invoke(ContainerInterface $container): EntityManagerInterface
    {
        if (!$container->has('settings.entity_directory')) {
            throw new \RuntimeException("La clé de configuration 'settings.entity_directory' est manquante dans le conteneur.");
        }

        $entityDir = $container->get('settings.entity_directory');
        if (!file_exists($entityDir) || !is_dir($entityDir)) {
            throw new \RuntimeException(sprintf("Le dossier contenant les entités n'existe pas ou n'est pas un répertoire valide : %s", $entityDir));
        }

        $dbal = new DatabaseManager(new DatabaseConfig([
            'default' => 'default',
            'databases' => [
                'default' => ['connection' => 'mysql'],
            ],
            'connections' => [
                'mysql' => new Config\MySQLDriverConfig(
                    connection: new Config\MySQL\TcpConnectionConfig(
                        database: $_ENV['DATABASE_NAME'] ?? 'framework_blog',
                        host: $_ENV['DATABASE_HOST'] ?? 'db',
                        port: (int) ($_ENV['DATABASE_PORT'] ?? 3306),
                        user: $_ENV['DATABASE_USER'] ?? 'user',
                        password: $_ENV['DATABASE_PASSWORD'] ?? 'password',
                        charset: 'utf8mb4',
                    ),
                    queryCache: true,
                ),
            ],
        ]));

        $classLocator = (new Tokenizer\Tokenizer(new Tokenizer\Config\TokenizerConfig([
            'directories' => [$entityDir],
        ])))->classLocator();

        $entityLocator = new TokenizerEntityLocator($classLocator);
        $embeddingLocator = new TokenizerEmbeddingLocator($classLocator);

        $schema = (new \Cycle\Schema\Compiler())->compile(
            new \Cycle\Schema\Registry($dbal),
            [
                // new \Cycle\Schema\Generator\ResetTables(),
                new Annotated\Embeddings($embeddingLocator),
                new Annotated\Entities($entityLocator),
                new Annotated\TableInheritance(),
                new Annotated\MergeColumns(),
                new Schema\Generator\GenerateRelations(),
                new Schema\Generator\GenerateModifiers(),
                new Schema\Generator\ValidateEntities(),
                new Schema\Generator\RenderTables(),
                new Schema\Generator\RenderRelations(),
                new Schema\Generator\RenderModifiers(),
                new Schema\Generator\ForeignKeys(),
                new Annotated\MergeIndexes(),
                new Schema\Generator\SyncTables(),
                new Schema\Generator\GenerateTypecast(),
            ],
        );

        $orm = new ORM(
            factory: new CycleFactory($dbal, RelationConfig::getDefault(), null),
            schema: new \Cycle\ORM\Schema($schema),
        );

        return new CycleEntityManager($orm);
    }
}
