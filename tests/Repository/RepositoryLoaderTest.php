<?php

/*
 * UserFrosting Support (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/support/blob/master/LICENSE.md (MIT License)
 */

use PHPUnit\Framework\TestCase;
use UserFrosting\Support\Exception\FileNotFoundException;
use UserFrosting\Support\Exception\JsonException;
use UserFrosting\Support\Repository\Loader\ArrayFileLoader;
use UserFrosting\Support\Repository\Loader\YamlFileLoader;
use UserFrosting\Support\Repository\PathBuilder\SimpleGlobBuilder;
use UserFrosting\UniformResourceLocator\ResourceLocator;

class RepositoryLoaderTest extends TestCase
{
    protected $basePath;

    protected $locator;

    protected $targetData = [
        'voles' => [
            'caught'   => 8,
            'devoured' => 8,
        ],
        'plumage' => 'floofy',
        'chicks'  => 4,
    ];

    public function setUp()
    {
        $this->basePath = __DIR__.'/data';
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

    /**
     */
    public function testYamlFileLoader()
    {
        // Arrange
        $builder = new SimpleGlobBuilder($this->locator, 'owls://');
        $loader = new YamlFileLoader($builder->buildPaths('yaml'));

        // Act
        $data = $loader->load(false);

        $this->assertEquals($this->targetData, $data);
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testYamlFileLoaderWithGetPaths()
    {
        $data = [
            __DIR__.'data/core/owls/megascops.yaml',
            __DIR__.'data/core/owls/tyto.yaml',
        ];

        $loader = new YamlFileLoader($data);

        $this->assertSame($data, $loader->getPaths());
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testYamlFileLoaderWithStringPath()
    {
        $loaderA = new YamlFileLoader([__DIR__.'data/core/owls/tyto.yaml']);
        $loaderB = new YamlFileLoader(__DIR__.'data/core/owls/tyto.yaml');

        $this->assertSame($loaderA->load(), $loaderB->load());
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testYamlFileLoaderWithPrependPath()
    {
        $loaderA = new YamlFileLoader([
            __DIR__.'data/core/owls/megascops.yaml',
            __DIR__.'data/core/owls/tyto.yaml',
        ]);
        $loaderB = new YamlFileLoader(__DIR__.'data/core/owls/megascops.yaml');
        $loaderB->prependPath(__DIR__.'data/core/owls/tyto.yaml');

        $this->assertSame($loaderA->load(), $loaderB->load());
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testYamlFileLoaderWithFileNotFoundException()
    {
        // Arrange
        $loader = new YamlFileLoader([
            __DIR__.'data/core/owls/dontExist.yaml',
        ]);

        // Expectations
        $this->expectException(FileNotFoundException::class);

        // Act
        $data = $loader->load(false);
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testYamlFileLoaderWithSkipMissing()
    {
        // Arrange
        $loader = new YamlFileLoader([
            __DIR__.'data/core/owls/dontExist.yaml',
        ]);

        // Act
        $data = $loader->load(true);

        $this->assertEmpty($data);
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testLoadYamlWithJsonData()
    {
        // Arrange
        $builder = new SimpleGlobBuilder($this->locator, 'owls://');
        $loader = new YamlFileLoader($builder->buildPaths('json'));

        // Act
        $data = $loader->load(false);

        $this->assertEquals([
            'plumage' => 'floofy',
        ], $data);
    }

    /**
     * @depends testYamlFileLoader
     */
    public function testLoadYamlWithPhpData()
    {
        // Arrange
        $builder = new SimpleGlobBuilder($this->locator, 'owls://');
        $loader = new YamlFileLoader($builder->buildPaths('php'));

        // This will throw a JsonException
        $this->expectException(JsonException::class);

        // Act
        $data = $loader->load(false);
    }
}
