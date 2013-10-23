<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * Check class.
 */
class Check extends AbstractResource
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

    public function testUrl($debug = false)
    {
        $params = array();
        if ($debug === true) {
            $params['debug'] = 'true';
        }
        return $this->getParent()->url('test-check', $params); 
    }

}