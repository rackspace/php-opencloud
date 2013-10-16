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

class NotificationPlan extends AbstractResource
{
    private $id;
	private $label;
	private $critical_state;
	private $ok_state;
	private $warning_state;
	
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notification_plans';

    protected static $requiredKeys = array(
        'label'
    );
    
    protected static $emptyObject = array(
        'label',
        'critical_state',
        'ok_state',
        'warning_state'
    );

}