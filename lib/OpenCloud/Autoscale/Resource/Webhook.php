<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * Description of Webhook
 * 
 * @link 
 */
class Webhook extends PersistentObject
{
    
    public $id;
    public $name;
    public $metadata;
    public $links;
    
    protected static $json_name = 'webhook';
    protected static $url_resource = 'webhooks';
    
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