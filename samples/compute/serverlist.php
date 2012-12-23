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

// list all servers
print("ALL SERVERS:\n");
$slist = $compute->ServerList();
while($server = $slist->Next())
    printf("* %-20s %-10s (%s)\n", 
		$server->Name(), $server->status, $server->ip());

// list all servers named MODEL
print("\nALL SERVERS NAMED 'MODEL':\n");
$slist = $compute->ServerList(TRUE, array('name'=>'MODEL'));
while($server = $slist->Next())
    printf("* %s\n", $server->name);
