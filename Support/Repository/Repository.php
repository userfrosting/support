<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/fortress
 * @license   https://github.com/userfrosting/fortress/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Support\Repository;

use Illuminate\Config\Repository as IlluminateRepository;

class Repository extends IlluminateRepository
{
    /**
     * Recursively merge locale values (scalar or array) into this repository.
     *
     * If no key is specified, the items will be merged in starting from the top level of the array.
     * If a key IS specified, items will be merged into that key.
     * Nested keys may be specified using dot syntax.
     * @param string|null $key
     * @param mixed $items
     */
    public function mergeItems($key = null, $items)
    {
        $targetValues = array_get($this->items, $key);

        if (is_array($targetValues)) {
            $modifiedValues = array_replace_recursive($targetValues, $items);
        } else {
            $modifiedValues = $items;
        }

        array_set($this->items, $key, $modifiedValues);
        return $this;
    }
}
