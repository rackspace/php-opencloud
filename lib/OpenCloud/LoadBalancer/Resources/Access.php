<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * sub-resource to manage access lists
 *
 * @api
 */
class Access extends SubResource 
{

    public $id;
    public $type;
    public $address;

    protected static $json_name = "accessList";
    protected static $url_resource = "accesslist";

    protected $_create_keys = array(
        'type', 
        'address'
    );

    public function Update($params = array()) 
    { 
        $this->NoUpdate(); 
    }

    /**
     * returns the JSON document's object for creating the subresource
     *
     * The value `$_create_keys` should be an array of names of data items
     * that can be used in the creation of the object.
     *
     * @return \stdClass;
     */
    protected function CreateJson()
    {
        $object = new \stdClass();
        $rule = new \stdClass();
        foreach ($this->_create_keys as $item) {
            $rule->$item = $this->$item;
        }
        $object->{$this->JsonName()} = array($rule);
        return $object;
    }
}
