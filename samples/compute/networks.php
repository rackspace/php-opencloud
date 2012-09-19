<?php
/**
 * (c)2012 Rackspace Hosting. See COPYING for license details
 *
 * The purpose of this smoketest is simply to ensure that the core
 * functionality of the library is present. It is not an exhaustive
 * integration test, nor is it a unit test. The goal is to rapidly
 * identify major problems if a code change breaks something.
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

// record the time
printf("SmokeTest started at %s\n", date(TIMEFORMAT, $start));

step('Authenticate');
$rackspace = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

step('Connect to Cloud Servers');
$cloudservers = $rackspace->Compute('cloudServersOpenStack', 'DFW');

step('Create a network SAMPLENET');
$samplenet = $cloudservers->Network();
$samplenet->Create(array(
    'label' => 'SAMPLENET',
    'cidr' => '192.168.0.0/28'));

step('List Networks');
$netlist = $cloudservers->NetworkList();
$netlist->Sort('id');
while($net = $netlist->Next())
	info('%s: %s (%s)', $net->id, $net->label, $net->cidr);

step('Create two servers on SAMPLENET');
$list = $cloudservers->ImageList(TRUE, array('name'=>'CentOS 6.3'));
$image = $list->First();
$flavor = $cloudservers->Flavor(2); // 512MB
$server1 = $cloudservers->Server();
$server1->Create(array(
    'name' => 'Server1',
    'image' => $image,
    'flavor' => $flavor,
    'networks' => array($samplenet, $cloudservers->Network(RAX_PUBLIC))));
$server2 = $cloudservers->Server();
$server2->Create(array(
    'name' => 'Server2',
    'image' => $image,
    'flavor' => $flavor,
    'networks' => array($samplenet, $cloudservers->Network(RAX_PUBLIC))));
$server1->WaitFor('ACTIVE', 300, 'dot');
print "\n";
$server2->WaitFor('ACTIVE', 300, 'dot');
print "\n";

step('DONE');
exit;

// callback for WaitFor
function dot($server) {
    printf("\r%s %s %3d%% %s",
        $server->id, $server->name, $server->progress, $server->status);
}
