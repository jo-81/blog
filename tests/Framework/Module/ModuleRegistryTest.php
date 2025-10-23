<?php

declare(strict_types=1);

namespace Tests\Framework\Module;

use PHPUnit\Framework\TestCase;
use Framework\Module\ModuleRegistry;
use Framework\Exception\ModuleException;
use Tests\Fixtures\Module\ExampleModule;

final class ModuleRegistryTest extends TestCase
{
    /**
     * testRegisterModule
     *
     * @return void
     */
    public function testRegisterModule(): void
    {
        $module = ExampleModule::class;
        $moduleRegistry = new ModuleRegistry();
        $moduleRegistry->registerModule($module);

        $this->assertCount(1, $moduleRegistry->getModules());
        $this->assertArrayHasKey('example.module', $moduleRegistry->getModules());

        // Ajout deux fois le même module
        $this->expectException(ModuleException::class);
        $this->expectExceptionMessage("Le module example.module est déjà présent.");

        $moduleRegistry->registerModule($module);
    }

    /**
     * testGetConfigFiles
     *
     * @return void
     */
    public function testGetConfigFiles(): void
    {
        $moduleRegistry = new ModuleRegistry();
        $this->assertEmpty($moduleRegistry->getConfigFiles());

        $module = ExampleModule::class;
        $moduleRegistry->registerModule($module);
        $this->assertCount(1, $moduleRegistry->getConfigFiles());
        $this->assertEquals($module::DEFINITION, $moduleRegistry->getConfigFiles()[0]);
    }
}
