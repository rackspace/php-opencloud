<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

define('IMAGE_ID', 'c195ef3b-9195-4474-b6f7-16e5bd86acd0');
define('FLAVOR_ID', '2');

require_once('rackspace.inc');
require_once('compute.inc');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

printf("Establish our credentials...\n");
$connection = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

printf("Connect to the compute service...\n");
$compute = $connection->Compute('cloudServersOpenStack', 'DFW');

printf("Get a server object...\n");
$server = $compute->Server();

printf("and create it...\n");
$server->Create(array(
	'name' => 'A simple server',
	'image' => $compute->Image(IMAGE_ID),
	'flavor' => $compute->Flavor(FLAVOR_ID)));

printf("The root password is [%s]\n", $server->adminPass);

printf("Wait for it to finish...\n");
$server->WaitFor('ACTIVE', 600, 'progress');

printf("DONE\n");
$server->Refresh();
printf("Server %s, IP %s\n", $server->Name(), $server->ip());
exit;

function progress($s) {
	printf("%3d%% complete, status is %s\n", $s->progress, $s->status);
}
