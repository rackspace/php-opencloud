<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * Contains generic, abstracted functionality for Autoscale resources.
 */
abstract class AbstractResource extends PersistentObject
{
    /**
     * These are used to set the object used for JSON encode. 
     * 
     * @var array 
     */
    public $createKeys = array();
    
    /**
     * These resources are associated with this one. When this resource object  
     * is populated, if a key is found matching one of these array keys, it is
     * set as an instantiated resource object (rather than an arbitrary string
     * or stdClass object).
     * 
     * @var array 
     */
    public $associatedResources = array();
    
    /**
     * Same as an associated resource, but it's instantiated as a Collection.
     * 
     * @var array 
     */
    public $associatedCollections = array();
    
    /**
     * Returns the URL for this resource.
     * 
     * @param  string|null $subResource
     * @param  bool        $includeId
     * @return string
     */
    public function url($subResource = null, $includeId = true)
    {
        $url = $this->parent()->url($this->resourceName());
        
        if ($includeId && $this->id) {
            $url .= '/' . $this->id;
        }
        
        if ($subResource) {
            $url .= '/' . $subResource;
        }
        
        return $url;
    }
    
    /**
     * Creates the object which will be JSON encoded for request.
     * 
     * @return \stdClass
     */
    public function createJson() 
    {
        $object = new \stdClass;

        foreach ($this->createKeys as $key) {
            if (!empty($this->$key)) {
                $object->$key = $this->$key;
            }
        }
        
        if (!empty($this->metadata)) {
            $object->metadata = new \stdClass;
            foreach ($this->metadata as $key => $value) {
                $object->metadata->$key = $value;
            }
        }

        return $object;
    }
    
    /**
     * Creates the object which will be JSON encoded for request.
     * 
     * @return array
     */
    protected function updateJson($params = array())
    {
        $existing = array();
        foreach ($this->createKeys as $key) {
            $existing[$key] = $this->$key;
        }
        
        return $existing + $params;
    }
    
    /**
     * Factory method for returning a resource. This is mostly used when a 
     * Collection instantiates an individual resource (i.e. in next() calls).
     * 
     * @param  string $name
     * @param  string $info
     * @return AbstractResource
     */
    public function resource($name, $info)
    {
        return $this->getService()->resource($name, $info);
    }
    
}