<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * Entity class.
 * 
 * @extends AbstractResource
 */
class Entity extends AbstractResource
{
    
	private $id;
	private $label;
	private $agent_id;
	private $ip_addresses;
	private $metadata;

    protected static $json_name = false;
    protected static $url_resource = 'entities';
    protected static $json_collection_name = 'values';

    protected static $emptyObject = array(
        'label',
        'agent_id',
        'ip_addresses',
        'metadata'
    );

    protected static $requiredKeys = array(
        'label'
    );

}