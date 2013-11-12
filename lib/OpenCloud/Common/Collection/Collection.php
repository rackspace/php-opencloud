<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Collection;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

/**
 * Class Collection
 * @since 1.8.0
 * @package OpenCloud\Common\Collection
 */
class Collection implements ArrayAccess
{

    protected $elements;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->elements = $data;
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
        return array_search($this->elements, $value) !== false;
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