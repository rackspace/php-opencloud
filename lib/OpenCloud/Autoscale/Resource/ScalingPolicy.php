<?php

/**
 * PHP OpenCloud library.
 *
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @version   2.0.0
 * @copyright Copyright 2012-2013 Rackspace US, Inc.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 */

namespace OpenCloud\Autoscale\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * Description of ScalingPolicy
 * 
 * @link 
 */
class ScalingPolicy extends PersistentObject
{
    
    public $id;
    public $links;
    public $name;
    public $change;
    public $cooldown;
    public $type;
    
    protected static $json_name = 'policy';
    protected static $json_collection_name = 'policies';
    protected static $url_resource = 'policies';
    protected static $json_collection_element = 'data';
    
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
    
    public function getWebhookList()
    {
        return $this->service()->resourceList('Webhook', null, $this);
    }
    
    public function getWebhook()
    {
        $config = new Webhook($this);
        $config->setParent($this);
        $config->setService($this->service()); 
        return $config;
    }
    
    public function webhook($info)
    {
        $webhook = $this->getWebhook();
        $webhook->populate($info);
        return $webhook;
    }
    
}