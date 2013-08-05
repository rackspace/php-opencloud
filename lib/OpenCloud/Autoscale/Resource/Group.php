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
use OpenCloud\Common\Exceptions;

/**
 * Description of Group
 * 
 * @link 
 */
class Group extends PersistentObject
{
    
    public $id;
    public $links;
    public $groupConfiguration;
    public $launchConfiguration;
    public $scalingPolicies;
    public $name;
    public $metadata;
    
    public $active;
    public $activeCapacity;
    public $pendingCapacity;
    public $desiredCapacity;
    public $paused;
    
    protected static $json_name = 'group';
    protected static $url_resource = 'groups';
    protected static $json_collection_name = 'groups';
    
    /**
     * These are used to set the object used for JSON encode. 
     * 
     * @var array 
     */
    private $createKeys = array(
        'groupConfiguration',
        'launchConfiguration',
        'scalingPolicies'
    );
    
    /**
     * These resources are associated with this one. When this resource object  
     * is populated, if a key is found matching one of these array keys, it is
     * set as an instantiated resource object (rather than an arbitrary string
     * or stdClass object).
     * 
     * @var array 
     */
    public $associatedResources = array(
        'groupConfiguration'  => 'GroupConfiguration',
        'launchConfiguration' => 'LaunchConfiguration',
        
    );
    
    public $associatedCollections = array(
        'scalingPolicies' => 'ScalingPolicy'
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
    
    public function create($params = array())
    {
        if (is_string($params)) {
            $params = json_decode($params);
            $this->checkJsonError();
        } elseif (!is_object($params) && !is_array($params)) {
            throw new Exceptions\InvalidArgumentError(
                'You must provide either a string or an array/object'
            );
        }

        $this->populate($params, false);

        return parent::create();
    }
    
    protected function createJson() 
    {
        $object = new \stdClass;

        foreach ($this->createKeys as $key) {
            if (!empty($this->$key)) {
                $object->$key = $this->$key;
            }
        }
        
        if (is_array($this->metadata) && count($this->metadata)) {
            $object->metadata = new \stdClass;
            foreach($this->metadata as $key => $value) {
                $object->metadata->$key = $value;
            }
        }

        return $object;
    }
    
    public function resource($name, $info)
    {
        return $this->service()->resource($name, $info);
    }
    
    public function getState()
    {
        $url = $this->url('state', true);
        
        $response = $this->service()->request($url, 'GET');

        if (!is_object($response)) {
            throw new Exceptions\HttpError(sprintf(
                Lang::translate('Invalid response for %s::Action() request'),
                get_class($this)
            ));
        }

        // check for errors
        if ($response->HttpStatus() >= 300) {
            throw new Exceptions\ServerActionError(sprintf(
                Lang::translate('%s::Action() [%s] failed; response [%s]'),
                get_class($this),
                $url,
                $response->HttpBody()
            ));
        }

        $object = json_decode($response->httpBody());
        
        if (!empty($object->group)) {
            return $object->group;
        }
        return false;
    }
    
    public function getGroupConfig()
    {
        if ($this->groupConfiguration instanceof GroupConfiguration) {
            return $this->groupConfiguration;
        }
        
        $config = $this->getService()->resource('GroupConfiguration');
        $config->setParent($this);
        $config->refresh(null, $config->url());
        return $config;
    }
    
    public function getLaunchConfig()
    {
        if ($this->launchConfiguration instanceof LaunchConfiguration) {
            return $this->launchConfiguration;
        }
        
        $config = $this->getService()->resource('LaunchConfiguration');
        $config->setParent($this);
        $config->refresh(null, $config->url());
        return $config;
    }
    
    public function pause()
    {
        return $this->customAction($this->url('pause', true), 'POST');
    }
    
    public function resume()
    {
        return $this->customAction($this->url('resume', true), 'POST');
    }
    
    public function getPolicies()
    {
        return $this->service()->resourceList('ScalingPolicy', null, $this);
    }
    
    public function getPolicy($id = null)
    {
        $config = $this->getService()->resource('ScalingPolicy');
        $config->setParent($this);
        if ($id) {
            $config->populate($id);
        }
        return $config;
    }
    
}