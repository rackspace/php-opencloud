<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;

/**
 * Agent class.
 * 
 * @extends ReadOnlyResource
 * @implements ResourceInterface
 */
class AgentTarget extends ReadOnlyResource implements ResourceInterface
{
    
    private $type = 'agent.filesystem';
    
    protected static $json_name = 'targets';
    protected static $json_collection_name = 'targets';
    protected static $url_resource = 'targets';

    protected $allowedTypes = array(
        'agent.filesystem',
        'agent.memory',
        'agent.load_average',
        'agent.cpu',
        'agent.disk',
        'agent.network',
        'agent.plugin'
    );

    public function url($subresource = null, $queryString = array())
    {
        $resourceUrl = "agent/check_types/{$this->type}/{$this->resourceName()}";
        return $this->getParent()->url($resourceUrl);
    }

    public function setType($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new Exception\AgentException(sprintf(
                'Incorrect target type. Please specify one of the following: %s',
                implode(', ', $this->allowedTypes)
            ));
        }

        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function listAll()
    {
        $response = $this->getClient()->get($this->url())->send();
        
        $object = $response->getBody(true);

        if (isset($object->{self::$json_collection_name})) {
            $response = $object->{self::$json_collection_name};
        }

        return $response;
    } 
    
}