<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 */
namespace UserFrosting\Support\Repository;

use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;
use UserFrosting\Support\Exception\FileNotFoundException;

abstract class RepositoryLoaderFileSystem
{
    /**
     * @var array An array of paths to ultimately load the data from.
     */
    protected $paths = [];

    /**
     * @var UniformResourceLocator Locator service to use when searching for files.
     */
    protected $locator;

    /**
     * @var string Virtual path to search in the locator.
     */
    protected $uri;

    /**
     * Create the loader.
     * @param RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator $locator
     */
    public function __construct($locator, $uri)
    {
        $this->locator = $locator;
        $this->uri = $uri;

        $this->buildPathsFromLocator();
    }

    /**
     * Build out the ordered list of file paths, using the designated locator and uri for this loader.
     */
    abstract protected function buildPathsFromLocator();

    /**
     * Fetch content from a single file path.
     *
     * @param string $path
     * @return array
     */
    abstract protected function parseFile($path);

    /**
     * Fetch and recursively merge in content from all file paths.
     *
     * @return array
     */
    public function load($skipMissing = true)
    {
        $result = [];

        foreach ($this->paths as $path) {
            $contents = $this->loadFile($path, $skipMissing);
            $result = array_replace_recursive($result, $contents);
        }

        return $result;
    }

    /**
     * Fetch content from a single file path.
     *
     * @param string $path
     * @param bool $skipMissing True to ignore bad file paths.  If set to false, will throw an exception instead.
     * @return array
     * @throws FileNotFoundException
     */
    public function loadFile($path, $skipMissing = true)
    {
        if (!file_exists($fileWithPath)) {
            if ($skipMissing) {
                return [];
            } else {
                throw new FileNotFoundException("The repository file '$path' could not be found.");
            }
        }

        // If the file exists but is not readable, we always throw an exception.
        if (!is_readable($path)) {
            throw new FileNotFoundException("The repository file '$path' exists, but it could not be read.");
        }

        return $this->parseFile($path);
    }

    /**
     * Add a file path to the top of the stack.
     *
     * @param string $path
     */
    public function addPath($path)
    {
        $this->paths[] = rtrim($path, '/\\');
        return $this;
    }

    /**
     * Add a file path to the bottom of the stack.
     *
     * @param string $path
     */
    public function prependPath($path)
    {
        array_unshift($this->paths[], rtrim($path, '/\\'));
        return $this;
    }

    /**
     * Set the internal array of file paths.
     *
     * @param array $paths
     */
    public function setPaths($paths)
    {
        if (!is_array($paths)) {
            $paths = array($paths);
        }

        $this->paths = [];

        foreach ($paths as $path) {
            $this->addPath($path);
        }

        return $this;
    }

    /**
     * Return a list of all file paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }
}
