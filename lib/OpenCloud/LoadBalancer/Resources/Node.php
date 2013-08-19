<?php

namespace OpenCloud\LoadBalancer\Resources;

use OpenCloud\Common\PersistentObject;

/**
 * information on a single node in the load balancer
 *
 * This extends `PersistentObject` because it has an ID, unlike most other
 * sub-resources.
 */
class Node extends PersistentObject 
{
    
    public $id;
    public $address;
    public $port;
    public $condition;
    public $status;
    public $weight;
    public $type;

    protected static $json_name = FALSE;
    protected static $json_collection_name = 'nodes';
    protected static $url_resource = 'nodes';
    
    private $_create_keys = array(
        'address',
        'port',
        'condition',
        'type',
        'weight'
    );

    /**
     * returns the Node name
     *
     * @return string
     */
    public function Name() 
    {
        return get_class() . '[' . $this->Id() . ']';
    }

    /**
     * returns the object for the Create JSON
     *
     * @return \stdClass
     */
    protected function CreateJson() 
    {
        $object = new \stdClass();
        $object->nodes = array();
        $node = new \stdClass();
        $node->node = new \stdClass();
        foreach($this->_create_keys as $key) {
            $node->node->$key = $this->$key;
        }
        $object->nodes[] = $node;
        return $object;
    }

    /**
     * factory method to create a new Metadata child of the Node
     *
     * @api
     * @return Metadata
     */
    public function Metadata($data = null) 
    {
        return new Metadata($this, $data);
    }

    /**
     * factory method to create a Collection of Metadata object
     *
     * Note that these are metadata children of the Node, not of the
     * LoadBalancer.
     *
     * @api
     * @return Collection of Metadata
     */
    public function MetadataList() 
    {
        return $this->Service()->Collection('\OpenCloud\LoadBalancer\Resources\Metadata', null, $this);
    }
    
}
