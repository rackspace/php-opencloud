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
 * CheckType class.
 */
class CheckType extends ReadOnlyResource
{
	
	private $id;
	private $type;
	private $fields;
	private $supported_platforms;

    protected static $json_name = false;
    protected static $url_resource = 'check_types';
    protected static $json_collection_name = 'values';

}