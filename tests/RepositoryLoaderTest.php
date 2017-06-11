<?php

use PHPUnit\Framework\TestCase;
use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;
use UserFrosting\Support\Repository\SimpleGlobLoader;

class RepositoryLoaderTest extends TestCase
{
    protected $basePath;

    protected $locator;

    public function setUp()
    {
        $this->basePath = __DIR__ . '/data';
        $this->locator = new UniformResourceLocator($this->basePath);

        // Add them one at a time to simulate how they are added in SprinkleManager
        $this->locator->addPath('owls', '', 'core/owls');
        $this->locator->addPath('owls', '', 'account/owls');
        $this->locator->addPath('owls', '', 'admin/owls');
    }

    public function testGlobOne()
    {
        // Act
        $loader = new SimpleGlobLoader($this->locator, 'owls://');
        $paths = $loader->getPaths();

        // Assert
        $this->assertEquals($paths, [
            $this->basePath . '/core/owls/megascops.php',
            $this->basePath . '/core/owls/tyto.php',
            $this->basePath . '/account/owls/megascops.php',
            $this->basePath . '/admin/owls/megascops.php'
        ]);
    }
}
