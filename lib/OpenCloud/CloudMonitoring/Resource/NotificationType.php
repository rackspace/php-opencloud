<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * NotificationType class.
 * 
 * @extends ReadOnlyResource
 * @implements ResourceInterface
 */
class NotificationType extends ReadOnlyResource implements ResourceInterface
{
    private $id;
    private $address;
    private $fields;
    
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notification_types';
    
    public function baseUrl()
    {
        return $this->getService()->url($this->resourceName());
    }

}