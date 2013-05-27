<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;

class CheckType extends PersistentObject
{
	
	public $id;
	public $type;
	public $fields;
	public $supported_platforms;

    protected static $json_name = 'check_types';
    protected static $url_resource = 'check_types';
    protected static $json_collection_name = 'values';

    public function Url($subresource = '')
    {
        return $this->Service()->Url($this->ResourceName()) . "/$subresource";
    }

    public function listAll($details = true, array $filter = array())
    {
        if (!is_bool($details)) {
            throw new Exceptions\InvalidArgumentException(
            	Lang::translate('First argument for Compute::ServerList() must be boolean'
            ));
        }

        if (!is_array($filter)) {
            throw new Exceptions\InvalidArgumentException(
            	Lang::translate('Second argument for Compute::ServerList() must be array'
            ));
        }

        $url = $this->Url(self::ResourceName(), $filter);

        return $this->Service()->Collection(get_class(), $url);
    }

    public function get($id)
    {
        $response = $this->Service()->Request($this->Url($id), 'GET');

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

}