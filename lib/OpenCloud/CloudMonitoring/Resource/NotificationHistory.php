<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;

/**
 * NotificationHistory class.
 * 
 * @extends ReadOnlyResource
 * @implements ResourceInterface
 */
class NotificationHistory extends ReadOnlyResource implements ResourceInterface
{
    private $timestamp;
    private $notification_plan_id;
    private $transaction_id;
    private $status;
    private $state;
    private $notification_results;
    private $previous_state;
    
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notification_history';

    public function baseUrl()
    {
        return $this->getParent()->url($this->getParent()->getId()) . '/notification_history';
    }

    public function listChecks()
    {
        $response = $this->getService()->request($this->url());
        return ($json = $response->httpBody()) ? json_decode($json) : false;
    }
    
    public function listHistory($checkId)
    {
        return $this->getService()->collection(get_class(), $this->url($checkId));
    }

    public function getSingleHistoryItem($checkId, $historyId)
    {
        $response = $this->getService()->request($this->url($checkId . '/' . $historyId));
        
        if ($json = $response->httpBody()) {
            $object = json_decode($json);
            foreach ($object as $key => $val) {
                $this->$key = $val;
            }
        }
        
        return false;
    }

}