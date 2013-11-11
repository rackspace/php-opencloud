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
class Collection implements ArrayAccess, IteratorAggregate
{

    protected $container;

    /**
     * @param array $data
     * @return $this
     */
    public static function factory(array $data = array())
    {
        $self = new self();
        return $self->setContainer($data);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setContainer(array $data = array())
    {
        $this->container = $data;
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
        $this->container[$offset] = $value;
    }

    /**
     * Appends a value to the container.
     *
     * @param $value
     */
    public function append($value)
    {
        $this->container[] = $value;
    }

    /**
     * Checks to see whether a particular offset key exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Checks to see whether a particular value exists.
     *
     * @param $value
     * @return bool
     */
    public function valueExists($value)
    {
        return array_search($this->container, $value) !== false;
    }

    /**
     * Unset a particular key.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Get the value for a particular offset key.
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->container[$offset] : null;
    }

    /**
     * @return \Traversable|void
     */
    public function getIterator()
    {
        return new ArrayIterator($this->container);
    }



}