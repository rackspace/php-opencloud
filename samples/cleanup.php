<?php
/**
 * (c)2012 Rackspace Hosting. See COPYING for license details
 *
 * This sample creates an isolated network called SAMPLENET. It then
 * creates two servers attached to that network. Once the servers are
 * created, it pauses to wait for you to verify the connectivity. When
 * it continues, it deletes the servers and SAMPLENET.
 */
$start = time();
ini_set('include_path', './lib:'.ini_get('include_path'));
require('rackspace.inc');
define('INSTANCENAME', 'SmokeTestInstance');
define('SERVERNAME', 'SmokeTestServer');

/**
 * Relies upon environment variable settings — these are the same environment
 * variables that are used by python-novaclient. Just make sure that they're
 * set to the right values before running this test.
 */
define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

/**
 * numbers each step
 */
function step($msg,$p1=NULL,$p2=NULL,$p3=NULL) {
    global $STEPCOUNTER;
    printf("\nStep %d. %s\n", ++$STEPCOUNTER, sprintf($msg,$p1,$p2,$p3));
}
function info($msg,$p1=NULL,$p2=NULL,$p3=NULL) {
    printf("  %s\n", sprintf($msg,$p1,$p2,$p3));
}
define('TIMEFORMAT', 'r');

print "This script deletes stuff created in other sample code scripts\n";

step('Authenticate');
$rackspace = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

step('Connect to Cloud Servers');
$cloudservers = $rackspace->Compute('cloudServersOpenStack', 'DFW');

step('Connect to CBS');
$cbs = $rackspace->VolumeService('cloudBlockStorage', 'DFW');

step('Connect to Cloud Files');
$files = $rackspace->ObjectStore('cloudFiles', 'DFW');

step('Deleting unused servers');
$list = $cloudservers->ServerList();
while($server = $list->Next())
    if ($server->name != 'MODEL') {
        info('Deleting server [%s] %s', $server->id, $server->Name());
        $server->Delete();
    }

step('Deleting unused volumes');
$list = $cbs->VolumeList();
while($vol = $list->Next()) {
	if ($vol->status == 'in-use')
		info('Volume [%s] %s is in use', $vol->id, $vol->Name());
	else {
		info('Deleting volume [%s] %s', $vol->id, $vol->Name());
		$vol->Delete();
	}
}

step('Deleting SAMPLENET and SMOKETEST Networks');
$list = $cloudservers->NetworkList();
while($network = $list->Next()) {
    if ($network->label=='SAMPLENET' || $network->label=='SMOKETEST') {
        info('Deleting network [%s] %s', $network->id, $network->label);
        $network->Delete();
    }
}

step('Deleting objects');
$list = $files->ContainerList();
while($container = $list->Next()) {
    info('Container: %s', $container->Name());
    $objlist = $container->ObjectList();
    if ($objlist->Size())
        info('Objects:');
    while($obj = $objlist->Next()) {
        info('%30s deleting...', $obj->Name());
        $obj->Delete();
    }
    info('Deleting container');
    $container->Delete();
}

step('DONE');
