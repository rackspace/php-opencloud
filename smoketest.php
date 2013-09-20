<?php
/**
 * (c)2012 Rackspace Hosting. See LICENSE for license details
 *
 * The purpose of this smoketest is simply to ensure that the core
 * functionality of the library is present. It is not an exhaustive
 * integration test, nor is it a unit test. The goal is to rapidly
 * identify major problems if a code change breaks something.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

$start = time();

if (strpos($_ENV['NOVA_URL'], 'staging.identity.api.rackspacecloud')) {
	define('RAXSDK_SSL_VERIFYHOST', 0);
	define('RAXSDK_SSL_VERIFYPEER', 0);
}
define('INSTANCENAME', 'SmokeTestInstance');
define('SERVERNAME', 'SmokeTestServer');
define('NETWORKNAME', 'SMOKETEST');
define('MYREGION', $_ENV['OS_REGION_NAME']);
define('VOLUMENAME', 'SmokeTestVolume');
define('VOLUMESIZE', 103);
define('LBNAME', 'SmokeTestLoadBalancer');
define('CACHEFILE', '/tmp/smoketest.credentials');
define('TESTDOMAIN', 'domain-'.time().'.info');
define('RAXSDK_STRICT_PROPERTY_CHECKS', false);

require_once 'lib/php-opencloud.php';


/**
 * Cloud DNS
 */


/**
 * Cloud Files
 */


/**
 * Cloud Load Balancers
 */


/**
 * Cloud Servers
 */


/**
 * Cloud Databases
 */


