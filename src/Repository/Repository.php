<?php

/*
 * UserFrosting Support (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/support/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Support\Repository;

use Illuminate\Config\Repository as IlluminateRepository;
use Illuminate\Support\Arr;
use UserFrosting\Support\Util\Util;

/**
 * Repository Class.
 *
 * Represents an extendable repository of key->value mappings.
 *
 * @author Alexander Weissman (https://alexanderweissman.com)
 */
class Repository extends IlluminateRepository
{
    /**
     * Recursively merge values (scalar or array) into this repository.
     *
     * If no key is specified, the items will be merged in starting from the top level of the array.
     * If a key IS specified, items will be merged into that key.
     * Nested keys may be specified using dot syntax.
     *
     * @param string|null $key
     * @param mixed       $items
     */
    public function mergeItems($key, $items): self
    {
        $targetValues = Arr::get($this->items, $key);

        if (is_array($targetValues)) {
            $modifiedValues = array_replace_recursive($targetValues, $items);
        } else {
            $modifiedValues = $items;
        }

        Arr::set($this->items, $key, $modifiedValues);

        return $this;
    }

    /**
     * Get the specified configuration value, recursively removing all null values.
     *
     * @param string|array|null $key
     *
     * @return mixed
     */
    public function getDefined($key = null)
    {
        $result = $this->get($key);
        if (!is_array($result)) {
            return $result;
        }

        return Util::arrayFilterRecursive($result, function ($value) {
            return !is_null($value);
        });
    }
}
