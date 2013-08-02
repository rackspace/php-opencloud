<?php

/**
 * PHP OpenCloud library.
 *
 * @author    Jamie Hannaford <jamie@limetree.org>
 * @version   2.0.0
 * @copyright Copyright 2012-2013 Rackspace US, Inc.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 */

namespace OpenCloud\Autoscale\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * Description of LaunchConfiguration
 * 
 * @link 
 */
class LaunchConfiguration extends PersistentObject
{
    
    public $type;
    public $args;
    
    protected static $json_name = 'launchConfiguration';
    protected static $url_resource = 'launch';
    
    protected $parent; 
    protected $service;
    
    public function parent()
    {
        return $this->parent;
    }
    
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    
    public function setService($service)
    {
        $this->service = $service;
    }
    
    public function service()
    {
        return $this->service;
    }
    
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
    
}