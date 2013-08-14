<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * Check class.
 * 
 * @extends AbstractResource
 */
class Check extends AbstractResource implements ResourceInterface
{
    public $id;
	public $type;
	public $details;
	public $disabled;
	public $label;
	public $metadata;
	public $period;
	public $timeout;
	public $monitoring_zones_poll;
	public $target_alias;
	public $target_hostname;
	public $target_resolver;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'checks';

    protected static $emptyObject = array(
        'type',
        'details',
        'disabled',
        'label',
        'metadata',
        'period',
        'timeout',
        'monitoring_zones_poll',
        'target_alias',
        'target_hostname',
        'target_resolver'
    );

    protected static $requiredKeys = array(
        'type'
    );
    
    protected $associatedResources = array(
        'CheckType' => 'CheckType'
    );

    public function baseUrl($subresource = '')
    {
        return $this->getParent()->Url() . '/' . $this->getParent()->id . '/'. $this->resourceName();
    }

    public function createUrl()
    {
        return $this->url();
    }

    public function testUrl($debug = false)
    {
        $url = $this->getParent()->Url() . '/' . $this->getParent()->id . '/test-check'; 
        return ($debug !== true) ? $url : $url . '?debug=true';
    }

}