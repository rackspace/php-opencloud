<?php
/**
 * Defines global constants and functions
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

/**
 * This file contains only configuration data such as constants.
 * You can override these constants by defining them BEFORE you including
 * any of the top-level files from the SDK.
 *
 * Definitions:
 * * RAXSDK_TIMEZONE - the default timezone for interpreting date/time requests

 * * RAXSDK_CONNECTTIMEOUT - the time (in seconds) to wait for a connection
 *      to a service
 * * RAXSDK_TIMEOUT - the max time (in seconds) to wait for an HTTP request
 *      to complete
 * * RAXSDK_SERVER_MAXTIMEOUT - the max time (in seconds) that a server
 *      will wait for a change in status (Server::WaitFor() method)
 * * RAXSDK_POLL_INTERVAL - how often (in seconds) the Server::WaitFor() method
 *      will poll for a status change
 * * RAXSDK_DEFAULT_IP_VERSION - the default IP version (4 or 6) to return for
 *      the server's primary IP address
 * * RAXSDK_OVERLIMIT_TIMEOUT - the max time (in seconds) to wait before
 *      retrying a request that has failed because of rate limits. If the
 *      next available time for the request is more than (X) seconds away,
 *      then the request will fail; otherwise, the request will sleep until
 *      available.
 */

if (!defined('RAXSDK_TIMEZONE'))
    define('RAXSDK_TIMEZONE', 'America/Chicago');

if (!defined('RAXSDK_DNS_ASYNC_TIMEOUT'))
	define('RAXSDK_DNS_ASYNC_TIMEOUT', 60);

if (!defined('RAXSDK_DNS_ASYNC_INTERVAL'))
	define('RAXSDK_DNS_ASYNC_INTERVAL', 1);

if (!defined('RAXSDK_CONNECTTIMEOUT'))
    define('RAXSDK_CONNECTTIMEOUT', 5);

if (!defined('RAXSDK_TIMEOUT'))
    define('RAXSDK_TIMEOUT', 60);

if (!defined('RAXSDK_SERVER_MAXTIMEOUT'))
    define('RAXSDK_SERVER_MAXTIMEOUT', 3600);

if (!defined('RAXSDK_POLL_INTERVAL'))
    define('RAXSDK_POLL_INTERVAL', 10);

if (!defined('RAXSDK_DEFAULT_IP_VERSION'))
    define('RAXSDK_DEFAULT_IP_VERSION', 4);

if (!defined('RAXSDK_OVERLIMIT_TIMEOUT'))
    define('RAXSDK_OVERLIMIT_TIMEOUT', 300);
/**
 * sets default (highly secure) value for CURLOPT_SSL_VERIFYHOST. If you
 * are using a self-signed SSL certificate, you can reduce this setting, but
 * you do so at your own risk.
 */
if (!defined('RAXSDK_SSL_VERIFYHOST'))
    define('RAXSDK_SSL_VERIFYHOST', 2);
/**
 * sets default (highly secure) value for CURLOPT_SSL_VERIFYPEER. If you
 * are using a self-signed SSL certificate, you can reduce this setting, but
 * you do so at your own risk.
 */
if (!defined('RAXSDK_SSL_VERIFYPEER'))
    define('RAXSDK_SSL_VERIFYPEER', TRUE);

/**
 * edit and uncomment this to set the default location of cacert.pem file
 */

/* these should not be overridden */
define('RAXSDK_VERSION', '1.7.0');
define('RAXSDK_ERROR', 'Error:');
define('RAXSDK_FATAL', 'FATAL ERROR:');
define('RAXSDK_TERMINATED', '*** PROCESSING HALTED ***');
define('RAXSDK_CONTENT_TYPE_JSON', 'application/json');
define('RAXSDK_URL_PUBLIC', 'publicURL');
define('RAXSDK_URL_INTERNAL', 'internalURL');
define('RAXSDK_URL_VERSION_INFO', 'versionInfo');
define('RAXSDK_URL_VERSION_LIST', 'versionList');

/**
 * definitions for Rackspace authentication endpoints
 */
define('RACKSPACE_US', 'https://identity.api.rackspacecloud.com/v2.0/');
define('RACKSPACE_UK', 'https://lon.identity.api.rackspacecloud.com/v2.0/');

/**
 * We can re-authenticate this many seconds before the token expires
 *
 * Set this to a higher value if your service does not cache tokens; if
 * it *does* cache them, then this value is not required.
 */
define('RAXSDK_FUDGE', 0);

/**
 * Readable constants
 */
define('RAXSDK_SOFT_REBOOT', 'soft');
define('RAXSDK_HARD_REBOOT', 'hard');
define('RAXSDK_DETAILS', TRUE);
define('RAXSDK_MAX_CONTAINER_NAME_LEN', 256);

/**
 * UUID of the Rackspace 'public' network
 */
define('RAX_PUBLIC','00000000-0000-0000-0000-000000000000');
/**
 * UUID of the Rackspace 'private' network
 */
define('RAX_PRIVATE','11111111-1111-1111-1111-111111111111');


/********** TIMEZONE MAGIC **********/

/**
 * This is called if there is an error getting the default timezone;
 * that means that the default timezone isn't set.
 * 
 * @codeCoverageIgnore
 */
function __raxsdk_timezone_set($errno, $errstr) {
	if ($errno==2)
		date_default_timezone_set(RAXSDK_TIMEZONE);
	else
		die(sprintf("Unknown error %d: %s\n", $errno, $errstr));
}
set_error_handler('\OpenCloud\__raxsdk_timezone_set');
@date_default_timezone_get();
restore_error_handler();