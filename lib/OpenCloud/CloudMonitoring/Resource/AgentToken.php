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
 * Agent class.
 * 
 * @extends ReadOnlyResource
 * @implements ResourceInterface
 */
class AgentToken extends AbstractResource implements ResourceInterface
{
    private $id;
    private $token;
    private $label;
    
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'agent_tokens';
    
    protected static $emptyObject = array(
        'label',
        'token'
    );

    protected static $requiredKeys = array();

}