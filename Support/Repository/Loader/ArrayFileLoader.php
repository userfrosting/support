<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 */
namespace UserFrosting\Support\Repository\Loader;

/**
 * Load files from a PHP array.
 *
 * @author Alexander Weissman (https://alexanderweissman.com)
 */
class ArrayFileLoader extends FileRepositoryLoader
{
    /**
     * @return array
     */
    protected function parseFile($path)
    {
        return require $path;
    }
}
