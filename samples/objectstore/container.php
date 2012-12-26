<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;


require_once('rackspace.inc');
require_once('objectstore.inc');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$connection = new Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

// now, connect to the ObjectStore service
$objstore = $connection->ObjectStore('cloudFiles', 'DFW');

// create a new container
print("Creating NewContainer\n");
$container = $objstore->Container();
$container->Create(array('name'=>'NewContainer'));

// get our containers
print("Containers:\n");
$conlist = $objstore->ContainerList();
while($container = $conlist->Next()) {
    printf("* %s\n", $container->name);
}

// get the CDN containers
print("All CDN containers:\n");
$cdnlist = $objstore->CDN()->ContainerList();
while($cdncontainer = $cdnlist->Next()) {
    printf("* %s (CDN)\n", $cdncontainer->name);
}
print("Only CDN-enabled containers:\n");
$cdnlist = $objstore->CDN()->ContainerList(array('enabled_only'=>TRUE));
while($cdncontainer = $cdnlist->Next()) {
    printf("* %s (CDN)\n", $cdncontainer->name);
}

// delete the container
$container = $objstore->Container('NewContainer');
$container->Delete();
