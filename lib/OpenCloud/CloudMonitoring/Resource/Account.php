<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * Account class.
 * 
 * @extends AbstractResource
 * @implements ResourceInterface
 */
class Account extends AbstractResource implements ResourceInterface
{

    public $metadata;
    public $webhook_token;

    protected static $json_name = false;
    protected static $url_resource = 'account';

    protected static $requiredKeys = array(
        'id',
        'metadata',
        'webhook_token'
    );

    public function baseUrl()
    {
        return $this->getParent()->Url($this->ResourceName());
    }
    
    public function updateUrl()
    {
        return $this->url();
    }
    
    public function create($params = array())
    {
        return $this->noCreate();
    }
    
    public function delete()
    {
        return $this->noDelete();
    }
    
}