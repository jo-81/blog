<?php

declare(strict_types=1);

namespace Tests\Framework;

use Framework\Application;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Framework\Exception\FileNotFoundException;

class ApplicationTest extends TestCase
{
    /**
     * testRegisterConfigWhenFolderNotExist
     *
     * @return void
     */
    public function testRegisterConfigWhenFolderNotExist(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage("Le fichier fileNotFound.php n'existe pas.");

        $app = new Application();
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

        $app = new Application();
        $app->registerFile($file);

        $this->assertCount(1, $app->getFiles());
        $this->assertStringContainsString("framework_test.php", $app->getFiles()[0]);

        // Enregistrer deux fois le même fichier
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Le fichier $file est déjà enregistré.");

        $app->registerFile($file);
    }

    /**
     * testInitApplicationWhenNotFileRegister
     *
     * @return void
     */
    public function testInitApplicationWhenNotFileRegister(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Aucun fichier de configuration n'est enregistré.");

        $app = new Application();
        $app->init();
    }

    /**
     * testInitApplication
     *
     * @return void
     */
    public function testInitApplication(): void
    {
        $app = new Application();
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

        $app = new Application();
        $app->getContainer();
    }

    /**
     * testEnvironment
     *
     * @return void
     */
    public function testEnvironment(): void
    {
        $app = new Application();

        $this->assertTrue($app->isDevelopment());
    }
}
