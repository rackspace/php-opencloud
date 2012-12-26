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
print("Creating CdnContainer\n");
$container = $objstore->Container();
$container->Create(array('name'=>'CdnContainer'));

// publish it to the CDN
print("Publishing to CDN...\n");
$cdnversion = $container->PublishToCDN();

printf("Container: %s\n", $container->name);
printf("      URL: %s\n", $container->Url());
printf("  CDN URL: %s\n", $container->CDNUrl());

// load this file into the CDN container
print("Creating a CDN object\n");
$object = $container->DataObject();
$object->Create(array('name'=>'FOOBAR'), __FILE__);
printf("  The CDN URL of the object is %s\n", $object->CDNUrl());
printf("The PublicURL of the object is %s\n", $object->PublicURL('SSL'));

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

// Purge the object, then delete the object
print("Purging and deleting the object\n");
setDebug(TRUE);
$object->PurgeCDN('glen.campbell@rackspace.com');
$object->Delete();
setDebug(FALSE);

// delete the container
print("Deleting the container\n");
$container = $objstore->Container('CdnContainer');
$container->Delete();

print("Done\n");
