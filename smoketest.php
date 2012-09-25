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
define('NETWORKNAME', 'SMOKETEST');

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
$rackspace->AppendUserAgent('(PHP SDK SMOKETEST)');

step('Connect to Cloud Servers');
$cloudservers = $rackspace->Compute('cloudServersOpenStack', 'DFW');

step('List Flavors');
$flavorlist = $cloudservers->FlavorList();
$flavorlist->Sort('id');
while($f = $flavorlist->Next())
    info('%s: %sMB', $f->name, $f->ram);

step('List Images');
$imagelist = $cloudservers->ImageList();
$imagelist->Sort('name');
while($i = $imagelist->Next()) {
    info($i->name);
    // save a CentOS image for later
    if (!isset($centos) && $i->metadata->os_distro == 'centos')
        $centos = $i;
}

step('Create Network');
$network = $cloudservers->Network();
$network->Create(array('label'=>NETWORKNAME, 'cidr'=>'192.168.0.0/24'));

step('List Networks');
$netlist = $cloudservers->NetworkList();
$netlist->Sort('label');
while($net = $netlist->Next())
	info('%s: %s (%s)', $net->id, $net->label, $net->cidr);

step('Create Server');
$server = $cloudservers->Server();
$server->Create(array(
    'name'=>SERVERNAME,
    'image'=>$centos,
    'flavor'=>$flavorlist->First(),
    'networks'=>array($network, $cloudservers->Network(RAX_PUBLIC))
));

step('Wait for Server create');
$server->WaitFor('ACTIVE', 300, 'dotter');

step('Reboot Server');
$server->Reboot();
$server->WaitFor('ACTIVE', 300, 'dotter');

step('List Servers');
$list = $cloudservers->ServerList();
$list->Sort('name');
while($s = $list->Next())
    info($s->name);

step('Deleting the test server(s)');
$list = $cloudservers->ServerList();
while($s = $list->Next()) {
    if ($s->name == SERVERNAME) {
        info('Deleting %s', $s->id);
        $s->Delete();
    }
}

step('Connect to Cloud Databases');
$dbaas = $rackspace->DbService('cloudDatabases', 'DFW', 'publicURL');

step('Get Database Flavors');
$dbflist = $dbaas->FlavorList();
$dbflist->Sort();
while($flavor = $dbflist->Next())
    info('%s - %dM', $flavor->name, $flavor->ram);

step('Creating a Database Instance');
$instance = $dbaas->Instance();
$instance->name = INSTANCENAME;
$instance->flavor = $dbaas->Flavor(1);
$instance->volume->size = 1;
$instance->Create();
$instance->WaitFor('ACTIVE', 300, 'dotter');

step('Is the root user enabled?');
if ($instance->IsRootEnabled())
	info('Yes');
else
	info('No');

step('Creating a new database');
$db = $instance->Database();
$db->Create(array('name'=>'fooDb'));

step('Creating a new database user');
$user = $instance->User();
$user->Create(array('name'=>'FOO','password'=>'BAR','databases'=>array('fooDb')));

step('List database instances');
$dblist = $dbaas->InstanceList();
while($dbitem = $dblist->Next())
	info('Database Instance: %s (id %s)', $dbitem->name, $dbitem->id);

step('Deleting the database user');
$user->Delete();

step('Deleting the database');
$db->Delete();

step('Deleting the test instance(s)');
$ilist = $dbaas->InstanceList();
while($inst = $ilist->Next())
    if ($inst->name == INSTANCENAME) {
        info('Deleting %s', $inst->id);
        $inst->Delete();
    }

step('Connect to Cloud Files');
$cloudfiles = $rackspace->ObjectStore('cloudFiles', 'DFW');

step('Create Container');
$container = $cloudfiles->Container();
$container->Create('SmokeTestContainer');

step('Publish Container to CDN');
$container->PublishToCDN(60); // 60-second TTL
info('CDN URL: %s', $container->CDNUrl());

step('Create Object from this file');
$object = $container->DataObject();
$object->Create(array('name'=>'SmokeTestObject','type'=>'text/plain'), __FILE__);

step('List Containers');
$list = $cloudfiles->ContainerList();
while($c = $list->Next())
    info('Container: %s', $c->name);

step('List Objects in Container %s', $container->name);
$list = $container->ObjectList();
while($o = $list->Next())
    info('Object: %s', $o->name);

step('Disable Container CDN');
$container->DisableCDN();

step('Delete Object');
$list = $container->ObjectList();
while($o = $list->Next()) {
    info('Deleting: %s', $o->name);
    $o->Delete();
}

step('Delete Container: %s', $container->name);
$container->Delete();

step('Deleting the test network(s)');
$list = $cloudservers->NetworkList();
while($network = $list->Next()) {
	if ($network->label == NETWORKNAME) {
		info('Deleting: %s %s', $network->id, $network->label);
		$network->Delete();
	}
}

step('FINISHED at %s in %d seconds', date(TIMEFORMAT), time()-$start);
exit();

/**
 * callback for WaitFor function, above
 */
function dotter($obj) {
    info('...waiting on %s/%s - %d%%',
		$obj->name,
		$obj->status,
		isset($obj->progress) ? $obj->progress : 0);
}
