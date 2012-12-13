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
function info($msg,$p1=NULL,$p2=NULL,$p3=NULL,$p4=NULL,$p5=NULL) {
    printf("  %s\n", sprintf($msg,$p1,$p2,$p3,$p4,$p5));
}
define('TIMEFORMAT', 'r');

step('Authenticate');
$rackspace = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));

step('Connect to the Load Balancer Service');
$lbservice = $rackspace->LoadBalancerService('cloudLoadBalancers', 'DFW');

// allowed domains
$adlist = $lbservice->AllowedDomainList();
while($ad = $adlist->Next()) {
	info('Allowed domain: [%s]', $ad->Name());
}

// list load balancers
$list = $lbservice->LoadBalancerList();
if ($list->Size()) {
	step('Load balancers:');
	while($lb = $list->Next()) {
		info('[%s] %s in %s', $lb->id, $lb->Name(), $lb->Region());
		info('  Status: [%s]', $lb->Status());
		
		// Nodes
		$list = $lb->NodeList();
		if ($list->Size() == 0)
			info('  No nodes');
		else {
			while($node = $list->Next()) {
				info('  Node: [%s] %s:%d %s/%s', 
					$node->Id(), $node->address, $node->port,
					$node->condition, $node->status);
			}
		}
		
		// NodeEvents
		$list = $lb->NodeEventList();
		if ($list->Size() == 0)
			info('  No node events');
		else {
			while($event = $list->Next()) {
				info('  * Event: %s (%s)', 
					$event->detailedMessage, $event->author);
			}
		}
	}
}
else
	step('There are no load balancers');

step('DONE');
exit;

function dot($obj) {
	info('...%s', $obj->Status());
}
