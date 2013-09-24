<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * NotificationHistory class.
 * 
 * @extends ReadOnlyResource
 * @implements ResourceInterface
 */
class NotificationHistory extends ReadOnlyResource implements ResourceInterface
{
    private $id;
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

    public function listChecks()
    {
        $response = $this->getService()->get($this->url())->send();
        return $response->getBody(true);
    }
    
    public function listHistory($checkId)
    {
        return $this->getService()->collection(get_class(), $this->url($checkId));
    }

    public function getSingleHistoryItem($checkId, $historyId)
    {
        $url = $this->url($checkId . '/' . $historyId);
        $response = $this->getClient()->get($url)->send();
        
        if (null !== ($decoded = $response->getBody(true))) {
            $this->populate($decoded);
        }
        return false;
    }

}