<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('rackspace.php');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$cloud = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

$dns = $cloud->DNS();
$dlist = $dns->DomainList();
while($domain = $dlist->Next()) {
	printf("%s [%s]\n",
		$domain->Name(), $domain->emailAddress);
	/*
	$async = $domain->Export();
	$async->WaitFor('COMPLETED', 300, 'disp_status', 1);
	print($async->response->contents);
	*/
	$rlist = $domain->RecordList();
	while($rec = $rlist->Next()) {
		$name = str_replace($domain->Name(), '', $rec->name);
		printf("  %s %d %s %s\n", 
			$rec->type, $rec->ttl, $name, $rec->data);
	}
}

exit();

function disp_status($obj) {
	printf("%s [%s]\n", $obj->Name(), $obj->Status());
}