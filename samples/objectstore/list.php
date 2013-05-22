<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once "php-opencloud.php";

define('AUTHURL', RACKSPACE_US);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$connection = new \OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

// now, connect to the ObjectStore service
$objstore = $connection->ObjectStore('cloudFiles', 'DFW');

// get our containers
$conlist = $objstore->ContainerList();
while($container = $conlist->Next()) {
    printf("\n%s\n", $container->name);
    $objlist = $container->ObjectList();
    while($o = $objlist->Next()) {
		printf(" * %s\n   size: %d type: %s modified: %s\n",
			$o->name, $o->bytes, $o->content_type, $o->last_modified);
    }
}

