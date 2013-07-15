<?php
/**
 * (c)2012 Rackspace Hosting. See COPYING for license details
 * This script attempts to validate an authentication bug that appears daily.
 *
 */
require('php-opencloud.php');

/**
 * Relies upon environment variable settings — these are the same environment
 * variables that are used by python-novaclient. Just make sure that they're
 * set to the right values before running this test.
 */
define('AUTHURL', $_ENV['NOVA_URL']);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

$rackspace = new \OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

while(TRUE) {
	$rackspace->Authenticate();
	$arr = $rackspace->ExportCredentials();
	printf("%s Token [%s] expires in %5d seconds\n", 
		date('r'),
		$arr['token'],
		$arr['expiration']-time());
	sleep(60);
}