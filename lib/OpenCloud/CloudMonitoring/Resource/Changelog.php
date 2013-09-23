<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * Changelog class.
 * 
 * @extends ReadOnlyResource
 */
class Changelog extends ReadOnlyResource
{
    private $id;
    private $timestamp;
    private $entity_id;
    private $alarm_id;
    private $check_id;
    private $state;
    private $analyzed_by_monitoring_zone_id;
    
    protected static $json_name = 'changelogs/alarms';
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'changelogs/alarms';
    
}