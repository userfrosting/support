<?php

/*
 * UserFrosting Support (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/support/blob/master/LICENSE.md (MIT License)
 */

use PHPUnit\Framework\TestCase;
use UserFrosting\UniformResourceLocator\ResourceLocator;
use UserFrosting\Support\Repository\Loader\ArrayFileLoader;
use UserFrosting\Support\Repository\Loader\YamlFileLoader;
use UserFrosting\Support\Repository\PathBuilder\SimpleGlobBuilder;

class FileRepositoryLoaderTest extends TestCase
{
    protected $basePath;

    protected $locator;

    protected $targetData = [
        'voles' => [
            'caught'   => 8,
            'devoured' => 8
        ],
        'plumage' => 'floofy',
        'chicks'  => 4
    ];

    public function setUp()
    {
        $this->basePath = __DIR__ . '/data';
        $this->locator = new ResourceLocator($this->basePath);

        $this->locator->registerStream('owls');

        // Add them one at a time to simulate how they are added in SprinkleManager
        $this->locator->registerLocation('core');
        $this->locator->registerLocation('account');
        $this->locator->registerLocation('admin');
    }

    public function testGlobLoadArrays()
    {
        // Arrange
        $builder = new SimpleGlobBuilder($this->locator, 'owls://');
        $loader = new ArrayFileLoader($builder->buildPaths());

        // Act
        $data = $loader->load();

        $this->assertEquals($this->targetData, $data);
    }

    public function testGlobLoadYaml()
    {
        // Arrange
        $builder = new SimpleGlobBuilder($this->locator, 'owls://');
        $loader = new YamlFileLoader($builder->buildPaths('yaml'));

        // Act
        $data = $loader->load();

        $this->assertEquals($this->targetData, $data);
    }
}
