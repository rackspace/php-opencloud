<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * Check class.
 * 
 * @extends AbstractResource
 */
class Check extends AbstractResource implements ResourceInterface
{
    private $id;
	private $type;
	private $details;
	private $disabled;
	private $label;
	protected $metadata;
	private $period;
	private $timeout;
	private $monitoring_zones_poll;
	private $target_alias;
	private $target_hostname;
	private $target_resolver;

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

    public function createUrl()
    {
        return $this->url();
    }

    public function testUrl($debug = false)
    {
        $params = array();
        if ($debug === true) {
            $params['debug'] = 'true';
        }
        return $this->getParent()->url('test-check', $params); 
    }

}