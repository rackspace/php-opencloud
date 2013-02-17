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
	printf("\n%s [%s]\n",
		$domain->Name(), $domain->emailAddress);
	// list records
	printf("Records:\n");
	$rlist = $domain->RecordList();
	while($rec = $rlist->Next()) {
		printf("- %s %d %s %s\n", 
			$rec->type, $rec->ttl, $rec->Name(), $rec->data);
	}
	printf("Subdomains:\n");
	// setDebug(TRUE);
	$slist = $domain->SubDomainList();
	setDebug(FALSE);
	while($dom = $slist->Next()) {
		printf("- %s [%s]\n", $dom->Name(), $dom->emailAddress);
	}
}
