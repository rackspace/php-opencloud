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

step('Connect to the Load Balancer Service');
$lbservice = $rackspace->LoadBalancerService('cloudLoadBalancers', 'DFW');

$list = $lbservice->LoadBalancerList();
if ($list->Size()) {
	step('Load balancers:');
	while($lb = $list->Next()) {
		info('%10s %s', $lb->id, $lb->Name());
		info('Status: [%s]', $lb->Status());
		info('Error page: [%s]', 
			substr($lb->ErrorPage()->content, 0, 50).'...');
		info('Max Connections: [%d]', $lb->Stats()->maxConn);
		info('Connection Logging: [%s]', 
			$lb->ConnectionLogging()->enabled ? 'on' : 'off');
		info('Turning it on...');
			$cl = $lb->ConnectionLogging();
			$cl->Update(array('enabled'=>TRUE));
			$lb->WaitFor('ACTIVE', 300, 'dot');
		info('Updating ConnectionThrottle...');
		$th = $lb->ConnectionThrottle();
		$th->rateInterval = 60;
		$th->maxConnectionRate = 500;
		$th->minConnections = rand(0, 100);
		$th->maxConnections = rand(0, 1000);
		$th->Update();
		info($th->Name());
		info('  Max Connection Rate: [%d]', $th->maxConnectionRate);
		info('  Max Connections: [%d]', $th->maxConnections);
		info('  Min Connections: [%d]', $th->minConnections);
		info('  Rate Interval: [%d]', $th->rateInterval);
		$sp = $lb->SessionPersistence();
		//print_r($sp);
		info('Deleting ConnectionThrottle...');
		$lb->Waitfor('ACTIVE', 300, 'dot');
		$th->Delete();
	}
}
else
	step('There are no load balancers');

step('DONE');
exit;

function dot($obj) {
	info('...%s', $obj->Status());
}
