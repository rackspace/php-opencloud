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
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

// now, connect to the compute service
$compute = $connection->Compute('cloudServersOpenStack', 'DFW');

// list the flavors
print("Flavors:\n");
$flist = $compute->FlavorList();
while($flavor = $flist->Next()) {
    printf("\t%s - %s\n", $flavor->id, $flavor->name);
}

// list the images
print("\n\nImages:\n");
$ilist = $compute->ImageList();
while($image = $ilist->Next()) {
    printf("\t%s - %s\n", $image->id, $image->name);
}
