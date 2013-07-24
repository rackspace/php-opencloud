<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require('php-opencloud.php');

define('AUTHURL', RACKSPACE_US);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$cloud = new \OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

// now, connect to the compute service
$autoscale = $cloud->Autoscale('autoscale', 'DFW');

print_r($autoscale);
