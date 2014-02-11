<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Common\Collection;

use ArrayAccess;
use Countable;

/**
 * A generic, abstract collection class that allows collections to exhibit array functionality.
 *
 * @package OpenCloud\Common\Collection
 * @since   1.8.0
 */
abstract class ArrayCollection implements ArrayAccess, Countable
{
    /**
     * @var array The elements being held by this iterator.
     */
    protected $elements;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->setElements($data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setElements(array $data = array())
    {
        $this->elements = $data;

        return $this;
    }

    /**
     * Sets a value to a particular offset.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    /**
     * Appends a value to the container.
     *
     * @param $value
     */
    public function append($value)
    {
        $this->elements[] = $value;
    }

    /**
     * Checks to see whether a particular offset key exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    /**
     * Checks to see whether a particular value exists.
     *
     * @param $value
     * @return bool
     */
    public function valueExists($value)
    {
        return array_search($value, $this->elements) !== false;
    }

    /**
     * Unset a particular key.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }

    /**
     * Get the value for a particular offset key.
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->elements[$offset] : null;
    }
}