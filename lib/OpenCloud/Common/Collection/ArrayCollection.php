<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Collection;

use Countable;
use OpenCloud\Common\ArrayAccess;

/**
 * A generic, abstract collection class that allows collections to exhibit array functionality.
 *
 * @package OpenCloud\Common\Collection
 */
abstract class ArrayCollection extends ArrayAccess implements Countable
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
     * Appends a value to the container.
     *
     * @param $value
     */
    public function append($value)
    {
        $this->elements[] = $value;
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
}