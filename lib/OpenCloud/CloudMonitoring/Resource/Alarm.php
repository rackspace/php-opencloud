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

use OpenCloud\CloudMonitoring\Exception;

/**
 * Alarm class.
 */
class Alarm extends AbstractResource
{
    private $id;
	private $check_id;
	private $notification_plan_id;
	private $criteria;
	private $disabled;
	private $label;
	protected $metadata;
	
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'alarms';
    
    protected static $requiredKeys = array(
        'check_id',
        'notification_plan_id'
    );
    
    protected static $emptyObject = array(
        'check_id',
        'notification_plan_id',
        'criteria',
        'disabled',
        'label',
        'metadata'
    );
    
    public function test($params = array(), $debug = false)
    {
        if (!isset($params['criteria'])) {
            throw new Exception\AlarmException(
                'Please specify a "criteria" value'
            );
        }
        
        if (!isset($params['check_data']) || !is_array($params['check_data'])) {
            throw new Exception\AlarmException(
                'Please specify a "check data" array'
            );
        }
        
        $url  = $this->getParent()->url('test-alarm');
        $body = json_encode((object) $params);
        
        return $this->getService()
            ->getClient()
            ->post($url, array(), $body)
            ->send()
            ->getDecodedBody();
    }

    public function getHistoryUrl()
    {
        return $this->getUrl()->addPath(NotificationHistory::resourceName());
    }

    public function getRecordedChecks()
    {
        $data = $this->getService()
            ->getClient()
            ->get($this->getHistoryUrl())
            ->send()
            ->getDecodedBody();

        return (isset($data->check_ids)) ? $data->check_ids : false;
    }

    public function getNotificationHistoryForCheck($checkId)
    {
        $url = $this->getHistoryUrl()->addPath($checkId);
        return $this->getService()->resourceList('NotificationHistory', $url, $this);
    }

    public function getNotificationHistoryItem($uuid)
    {
        return $this->getService()->resource('NotificationHistory', $uuid, $this);
    }
	
}