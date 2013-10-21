<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common;

/**
 * The Metadata class represents either Server or Image metadata
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Metadata extends Base 
{

    // array holding the names of keys that were set
    protected $metadata = array();    

    /**
     * This setter overrides the base one, since the metadata key can be
     * anything
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function __set($property, $value) 
    {
        // set the value and track the keys
        $this->metadata[$property] = $value;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->metadata)) {
            return $this->metadata[$key];
        }
    }
    
    public function propertyExists($property)
    {
        return isset($this->metadata[$property]);
    }
    
    public function getProperty($property)
    {
        return $this->propertyExists($property) ? $this->metadata[$property] : null;
    }
    
    public function setProperty($property, $value)
    {
        if ($this->propertyExists($property)) {
            $this->metadata[$property] = $value;
        }
    }
    
    public function __isset($property)
    {
        return array_key_exists($property, $this->metadata);
    }
    
    /**
     * Returns the list of keys defined
     *
     * @return array
     */
    public function keylist() 
    {
        return $this->metadata;
    }

    /**
     * Sets metadata values from an array, with optional prefix
     *
     * If $prefix is provided, then only array keys that match the prefix
     * are set as metadata values, and $prefix is stripped from the key name.
     *
     * @param array $values an array of key/value pairs to set
     * @param string $prefix if provided, a prefix that is used to identify
     *      metadata values. For example, you can set values from headers
     *      for a Container by using $prefix='X-Container-Meta-'.
     * @return void
     */
    public function setArray($values, $prefix = null) 
    {
        if (empty($values)) {
            return false;
        }
        
        foreach ($values as $key => $value) {
            if ($prefix && strpos($key, $prefix) === 0) {
                $key = substr($key, strlen($prefix));
            } 
            $this->metadata[$key] = $value;
        }
    }
    
    public function toArray()
    {
        return $this->metadata;
    }

}
