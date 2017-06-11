<?php

use PHPUnit\Framework\TestCase;
use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;
use UserFrosting\Support\Repository\Loader\ArrayFileLoader;
use UserFrosting\Support\Repository\PathBuilder\SimpleGlobBuilder;

class FileRepositoryLoaderTest extends TestCase
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

    public function testGlobLoadRepo()
    {
        // Arrange
        $builder = new SimpleGlobBuilder($this->locator, 'owls://');
        $loader = new ArrayFileLoader($builder->buildPaths());

        // Act
        $data = $loader->load();

        $this->assertEquals($data, [
            'voles' => [
                'caught' => 8,
                'devoured' => 8
            ],
            'plumage' => 'floofy',
            'chicks' => 4
        ]);
    }
}
