<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Service;

/**
 * Description of CatalogItem
 * 
 * @link 
 */
class CatalogItem
{
    
    private $name;
    private $type;
    private $endpoints;
    
    public static function factory($object)
    {
        $item = new self();
        $item->setName($object->name)
            ->setType($object->type)
            ->setEndpoints($object->endpoints);
        
        return $item;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function hasName($string)
    {
        return !strnatcasecmp($this->name, $string);
    }
    
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function hasType($string)
    {
        return !strnatcasecmp($this->type, $string);
    }
    
    public function setEndpoints($endpoints)
    {
        $this->endpoints = $endpoints;
        return $this;
    }
    
    public function getEndpoints()
    {
        return $this->endpoints;
    }
    
    public function getEndpointFromRegion($region)
    {
        foreach ($this->endpoints as $endpoint) {
            if (!isset($endpoint->region) || $endpoint->region == $region) {
                return $endpoint;
            }
        }
        
        throw new \OpenCloud\Common\Exceptions\EndpointError(sprintf(
            'This service [%s] does not have access to the [%s] endpoint.',
            $this->name,
            $region
        ));
    }
    
}