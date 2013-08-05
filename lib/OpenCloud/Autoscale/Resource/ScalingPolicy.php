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
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;

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
    //protected static $json_collection_element = 'data';
    
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
    
    public function create($params = array())
    {
        // debug
        $this->debug('%s::Create(%s)', get_class($this), $this->Name());

        // construct the JSON
        $json = json_encode($params);
        
        if ($this->CheckJsonError()) {
            return false;
        }

        $this->debug('%s::Create JSON [%s]', get_class($this), $json);

        // send the request
        $response = $this->getService()->request(
            $this->createUrl(),
            'POST',
            array('Content-Type' => 'application/json'),
            $json
        );
        
        // check the return code
        if ($response->httpStatus() > 204) {
            throw new Exceptions\CreateError(sprintf(
                Lang::translate('Error creating [%s] [%s], status [%d] response [%s]'),
                get_class($this),
                $this->Name(),
                $response->HttpStatus(),
                $response->HttpBody()
            ));
        }

        if ($response->HttpStatus() == "201" && ($location = $response->Header('Location'))) {
            // follow Location header
            $this->refresh(null, $location);
        } else {
            // set values from response
            $object = json_decode($response->httpBody());
            
            if (!$this->checkJsonError()) {
                $top = $this->jsonName();
                if (isset($object->$top)) {
                    $this->populate($object->$top);
                }
            }
        }

        return $response;
    }
    
    public function createJson()
    {
        $object = new \stdClass;

        foreach ($this->createKeys as $key) {
            if (!empty($this->$key)) {
                $object->$key = $this->$key;
            }
        }
       
        return $object;
    }
    
    public function getWebhookList()
    {
        return $this->service()->resourceList('Webhook', null, $this);
    }
    
    public function getWebhook($id = null)
    {
        $webhook = new Webhook();
        $webhook->setParent($this);
        $webhook->setService($this->service());
        if ($id) {
            $webhook->populate($id);
        }
        return $webhook;
    }
    
    public function webhook($info)
    {
        $webhook = $this->getWebhook();
        $webhook->populate($info);
        return $webhook;
    }
    
    public function execute()
    {
        return $this->customAction($this->url('execute', true), 'POST');
    }
    
}