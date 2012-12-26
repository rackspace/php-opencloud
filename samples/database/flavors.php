<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('rackspace.inc');
require_once('compute.inc');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$connection = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

// now, connect to the compute service
$dbservice = $connection->DbService('cloudDatabases', 'DFW');

// list the flavors
print("Flavors:\n");
$flist = $dbservice->FlavorList();
while($flavor = $flist->Next()) {
    printf("%5s - %-10s ram: %5dMB\n",
        $flavor->id, $flavor->name, $flavor->ram);
}

