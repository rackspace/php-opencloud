<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('rackspace.php');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// uncomment for debug output
setDebug(TRUE);

// establish our credentials
$cloud = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

$dns = $cloud->DNS();

// get the limits
printf("Limit Types:\n");
$types = $dns->LimitTypes();
foreach($types as $type) {
	printf("\t%s\n", $type);
	printf("Limits for %s:\n", $type);
	$limits = $dns->Limits($type);
	print_r($limits);
}
