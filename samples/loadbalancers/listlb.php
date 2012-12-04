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
		info('Url [%s]', $lb->Url());
		info('%10s %s in %s', $lb->id, $lb->Name(), $lb->Region());
		info('Status: [%s]', $lb->Status());
		info('Error page: [%s]',
			substr($lb->ErrorPage()->content, 0, 50).'...');
		info('Max Connections: [%d]', $lb->Stats()->maxConn);

		// usage
		info('Usage...');
		//setDebug(TRUE);
		$us = $lb->Usage();

		// access list
		info('AccessList...');
		setDebug(TRUE);
		$al = $lb->AccessList();
		setDebug(FALSE);
		while($a = $al->Next())
			info(': %s, %s', $a->type, $a->address);

		// virtual IPs
		//setDebug(TRUE);
		$vips = $lb->VirtualIpList();
		while($vip = $vips->Next()) {
			info('  Virtual IP [%s,%s] [%s]',
				$vip->type, $vip->ipVersion, $vip->address);
		}
		setDebug(FALSE);
		// connection logging
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
		$lb->Waitfor('ACTIVE', 300, 'dot');
		//print_r($sp);
		info('Deleting ConnectionThrottle...');
		$th->Delete();
		$lb->Waitfor('ACTIVE', 300, 'dot');
		//setDebug(TRUE);
		$cc = $lb->ContentCaching();
		//setDebug(FALSE);
		info('Content Caching: [%s]',
			$cc->enabled ? 'on' : 'off');
		info('Turning it ON...');
		$cc->enabled = TRUE;
		//setDebug(TRUE);
		$cc->Update();
		$lb->WaitFor('ACTIVE', 300, 'dot');
	}
}
else
	step('There are no load balancers');

step('DONE');
exit;

function dot($obj) {
	info('...%s', $obj->Status());
}
