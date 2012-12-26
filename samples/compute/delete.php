<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;


require_once('rackspace.inc');
require_once('compute.inc');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$connection = new Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

// now, connect to the compute service
$compute = $connection->Compute('cloudServersOpenStack', 'DFW');

// This script deletes all the servers except for the one named "MODEL"
$srvlist = $compute->ServerList(FALSE); // we don't need all the details
while($server = $srvlist->Next()) {
	printf("%s\n", $server->name);
	if ($server->name == 'MODEL')
		printf("--KEEPING\n");
	else {
		$response = $server->Delete();
		printf("--deleted, status=%d\n", $response->HttpStatus());
	}
}
