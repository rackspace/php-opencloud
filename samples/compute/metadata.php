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

// let's get a list of servers - with details
$serverlist = $compute->ServerList(RAXSDK_DETAILS);
while($server = $serverlist->Next()) {
	$metadata = $server->metadata();
	// set a value
	$metadata->random = "X".rand();
	$metadata->Update();
	// display the metadata
	printf("Server [%s] metadata:\n", $server->name);
	// print them all
	foreach($metadata as $name => $value)
		printf("  %s = %s\n", $name, $value);
	// now delete the random key
	$meta2 = $server->metadata('random');
	$meta2->Delete();
}
