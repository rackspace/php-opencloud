<?php

namespace OpenCloud\Orchestration\Resource;

use OpenCloud\Common\Resource\BaseResource;

class Event extends BaseResource
{
    protected static $url_resource = 'events';
    protected static $json_name = 'event';
    protected static $json_collection_name = 'events';

    protected $id;
    protected $event_time;
    protected $logical_resource_id;
    protected $resource_status;
    protected $resource_status_reason;

    /**
     * Unique ID for this event.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * When this event was logged
     *
     * @return string
     */
    public function getEventTime()
    {
        return $this->event_time;
    }

    /**
     * The logical resource that the event pertains to.
     * @return string
     */
    public function getLogicalResourceId()
    {
        return $this->logical_resource_id;
    }

    /**
     * Status of the resource at the time of this event.
     *
     * @return string
     */
    public function getResourceStatus()
    {
        return $this->resource_status;
    }

    /**
     * Reason for the resources status at the time of this event.
     *
     * @return string
     */
    public function getResourceStatusReason()
    {
        return $this->resource_status_reason;
    }
}