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

/**
 * Let's build a server. We want to have an OS of CentOS 6.0 or higher and a
 * Flavor with at least 1G of RAM
 */

// first, find the image
$ilist = $compute->ImageList();
$found = FALSE;
$distro = "org.openstack__1__os_distro";
$version = "org.openstack__1__os_version";
while (!$found && $image = $ilist->Next()) {
	printf("Checking image %s...", $image->name);
	if ($image->metadata->$distro == 'org.centos' &&
		$image->metadata->$version >= 6.0) {
		$found = TRUE;
		$myimage = $image;
		print("FOUND IT");
	}
	print("\n");
}

// next, find the flavor
$flist = $compute->FlavorList();
$found = FALSE;
while (!$found && $flavor = $flist->Next()) {
	printf("Checking flavor %s...", $flavor->name);
	if ($flavor->ram > 512 && $flavor->ram < 1048) {
		$myflavor = $flavor;
		$found = TRUE;
		print("FOUND IT");
	}
	print("\n");
}

// let's create the server
$server = $compute->Server();
$server->name = 'My New Server';
print("Creating server...");
$server->Create(array(
		'image' => $myimage,
		'flavor' => $myflavor));
print("requested, now waiting...\n");
print("ID=".$server->id."...\n");
$server->WaitFor("ACTIVE", 600, 'OpenCloud\dot');
print("done\n");
exit(0);

function dot($server) {
	printf("%s %3d%%\n", $server->status, $server->progress);
}
