<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * Notification class.
 * 
 * @extends AbstractResource
 */
class Notification extends AbstractResource implements ResourceInterface
{
    private $id;
    private $label;
    private $type;
    private $details;
    
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notifications';
    
    protected static $emptyObject = array(
        'label',
        'type',
        'details'
    );

    protected static $requiredKeys = array(
        'type',
        'details'
    );
    
    protected $associatedResources = array(
        'NotificationType' => 'NotificationType'
    );
        
    public function testUrl($debug = false)
    {
        return $this->getService()->url('test-notification');
    }
    
    public function testExisting($debug = false)
    {
        return $this->customAction($this->testUrl($debug), 'POST');
    }
    
}