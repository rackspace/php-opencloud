<?php
/**
 * (c)2012 Rackspace Hosting. See COPYING for license details
 *
 */
$start = time();
ini_set('include_path', './lib:'.ini_get('include_path'));
require('rackspace.inc');

/**
 * Relies upon environment variable settings — these are the same environment
 * variables that are used by python-novaclient. Just make sure that they're
 * set to the right values before running this test.
 */
define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

define('VOLUMENAME', 'SampleVolume');
define('VOLUMESIZE', 100);
define('SERVERNAME', 'CBS-test-server');

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

step('Authenticate');
$rackspace = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

step('Connect to the VolumeService');
$cbs = $rackspace->VolumeService('cloudBlockStorage', 'DFW');

step('Snapshotting volumes');
$list = $cbs->VolumeList();
while($vol = $list->Next()) {
	info('Volume: %s [%s] size=%d',
		$vol->display_name,
		$vol->display_description,
		$vol->size);
    $snap = $cbs->Snapshot();   // empty snapshot object
    info('  Creating snapshot');
    $snap->Create(array(
        'display_name' => $vol->Name().'Snapshot-'.time(),
        'volume_id' => $vol->id
    ));
}

step('DONE');
exit;

// callback for WaitFor
function dot($server) {
    printf("\r\t%s %s %3d%% %s",
        $server->id, $server->name, $server->progress, $server->status);
}
