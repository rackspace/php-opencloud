<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;


require_once('rackspace.php');
require_once('compute.php');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$cloud = new Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

//setDebug(TRUE);

$dns = $cloud->DNS();
$dlist = $dns->DomainList();
while($domain = $dlist->Next()) {
	printf("%30s [%s]\n",
		$domain->Name(), $domain->emailAddress);
}