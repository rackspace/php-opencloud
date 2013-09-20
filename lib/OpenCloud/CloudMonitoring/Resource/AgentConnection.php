<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\CloudMonitoring\Exception;

/**
 * AgentConnection class.
 * 
 * @extends ReadOnlyResource
 */
class AgentConnection extends ReadOnlyResource implements ResourceInterface
{

    private $guid;
    private $agent_id;
    private $endpoint;
    private $process_version;
    private $bundle_version;
    private $agent_ip;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'agents';
    
    /**
     * @codeCoverageIgnore
     */
    public function baseUrl()
    {
    }

}