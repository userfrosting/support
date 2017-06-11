<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 */
namespace UserFrosting\Support\Repository;

class SimpleGlobLoader extends RepositoryLoaderFileSystem
{
    /**
     * Glob together all file paths in each search path from the locator.
     */
    protected function buildPathsFromLocator()
    {
        // Get all paths from the locator that match the uri.
        // Put them in reverse order to allow later files to override earlier files.
        $searchPaths = array_reverse($this->locator->findResources($this->uri, true, true));

        $filePaths = [];
        foreach ($searchPaths as $path) {
            $globs = glob(rtrim($path, '/\\') . '/*.php');
            $filePaths = array_merge($filePaths, $globs);
        }

        $this->setPaths($filePaths);
    }

    /**
     * Basic example; loads content from a file that returns a PHP array.
     *
     * @return array
     */
    protected function parseFile($path)
    {
        return require $path;
    }
}
