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
        
    private $createKeys = array(
        'name',
        'metadata'
    );
    
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
    
    public function createJson()
    {
        $object = new \stdClass;

        $object->name = $this->name;
        $object->metadata = $this->metadata;
       
        return $object;
    }
    
    public function create($params = array())
    {
        // debug
        $this->debug('%s::Create(%s)', get_class($this), $this->Name());

        // construct the JSON
        $json = json_encode($params);

        if ($this->checkJsonError()) {
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
                $this->name(),
                $response->httpStatus(),
                $response->httpBody()
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
    
    protected function updateJson($params = array())
    {
        $existing = array();
        foreach ($this->createKeys as $key) {
            $existing[$key] = $this->$key;
        }
        
        return $existing + $params;
    }
    
}