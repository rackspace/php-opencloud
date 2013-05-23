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

// get our CDN containers
$conlist = $objstore->ContainerList(array('enabled_only'=>TRUE));
while($container = $conlist->Next()) {
    printf("\n(CDN) %s\n", $container->name);
    $objlist = $container->ObjectList();
    while($o = $objlist->Next()) {
		printf(" * %s\n   URL: %s\n",
			$o->name, $o->PublicURL());
    }
}

