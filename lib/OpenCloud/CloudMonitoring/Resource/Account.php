<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;

class Account extends PersistentObject
{

    public $id;
    public $metadata;
    public $webhook_token;

    protected static $json_name = 'account';
    protected static $url_resource = 'account';

    protected $_create_keys = array(
        'id',
        'metadata',
        'webhook_token'
    );

    public function Name()
    {
        return 'Account';
    }

    public function Url()
    {
        return $this->Parent()->Url($this->ResourceName());
    }

    public function get()
    {
        $response = $this->Service()->Request($this->Url(), 'GET');
        if ($json = $response->HttpBody()) {

            $resp = json_decode($json);

            if ($this->CheckJsonError()) {
                throw new \Exception(sprintf(
                    Lang::translate('JSON parse error on %s refresh'), 
                    get_class($this)
                ));
            }

            foreach($resp as $item => $value) {
                $this->$item = $value;
            }
        }
    }

    protected function UpdateJson($params = array()) 
    {
        $object = new \stdClass();
        foreach ($params as $key => $val) {
            $object->$key = $val;
        }
        return $object;
    }

}