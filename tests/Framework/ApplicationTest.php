<?php

declare(strict_types=1);

namespace Tests\Framework;

use Framework\Application;
use PHPUnit\Framework\TestCase;
use Framework\Module\ModuleRegistry;
use Psr\Container\ContainerInterface;
use Tests\Fixtures\Module\ExampleModule;
use Framework\Exception\FileConfigurationException;

class ApplicationTest extends TestCase
{
    private ModuleRegistry $moduleRegistry;

    protected function setUp(): void
    {
        $this->moduleRegistry = new ModuleRegistry();
    }

    /**
     * testRegisterConfigWhenFolderNotExist
     *
     * @return void
     */
    public function testRegisterConfigWhenFolderNotExist(): void
    {
        $this->expectException(FileConfigurationException::class);
        $this->expectExceptionMessage("Le fichier fileNotFound.php n'existe pas.");

        $app = new Application($this->moduleRegistry);
        $app->registerFile("fileNotFound.php");
    }

    /**
     * testRegisterConfigWhenFolderExist
     *
     * @return void
     */
    public function testRegisterConfigWhenFolderExist(): void
    {
        $file = \dirname(__DIR__) . "/config/framework_test.php";

        $app = new Application($this->moduleRegistry);
        $app->registerFile($file);

        $this->assertCount(1, $app->getFiles());
        $this->assertStringContainsString("framework_test.php", $app->getFiles()[0]);

        // Enregistrer deux fois le même fichier
        $this->expectException(FileConfigurationException::class);
        $this->expectExceptionMessage("Le fichier $file est déjà enregistré.");

        $app->registerFile($file);
    }

    /**
     * Si le fichier de configuration d'un module est ajouté lors de l'appel à init()
     *
     * @return void
     */
    public function testRegisterConfigWithModule(): void
    {
        $this->moduleRegistry->registerModule(ExampleModule::class);
        $app = new Application($this->moduleRegistry);
        $app->init();

        $this->assertCount(1, $app->getFiles());
        $this->assertStringContainsString("config.php", $app->getFiles()[0]);
        $this->assertInstanceOf(ExampleModule::class, $app->getContainer()->get(ExampleModule::class));
    }

    /**
     * testInitApplicationWhenNotFileRegister
     *
     * @return void
     */
    public function testInitApplicationWhenNotFileRegister(): void
    {
        $this->expectException(FileConfigurationException::class);
        $this->expectExceptionMessage("Aucun fichier de configuration n'est enregistré.");

        $app = new Application($this->moduleRegistry);
        $app->init();
    }

    /**
     * testInitApplication
     *
     * @return void
     */
    public function testInitApplication(): void
    {
        $app = new Application($this->moduleRegistry);
        $app
            ->registerFile(\dirname(__DIR__) . "/config/framework_test.php")
            ->init()
        ;

        $this->assertInstanceOf(ContainerInterface::class, $app->getContainer());

        // Initialisation du container deux fois
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Le container est déjà initialisé.");

        $app->init();
    }

    /**
     * testWhenContainerReturnNull
     *
     * @return void
     */
    public function testWhenContainerReturnNull(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Le container n'est pas encore initialisé. Appelez init() d'abord.");

        $app = new Application($this->moduleRegistry);
        $app->getContainer();
    }

    /**
     * testEnvironment
     *
     * @return void
     */
    public function testEnvironment(): void
    {
        $app = new Application($this->moduleRegistry);

        $this->assertTrue($app->isDevelopment());
    }
}
