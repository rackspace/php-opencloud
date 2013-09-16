<?php
/**
 * (c)2012 Rackspace Hosting. See LICENSE for license details
 *
 * The purpose of this smoketest is simply to ensure that the core
 * functionality of the library is present. It is not an exhaustive
 * integration test, nor is it a unit test. The goal is to rapidly
 * identify major problems if a code change breaks something.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

$start = time();

if (strpos($_ENV['NOVA_URL'], 'staging.identity.api.rackspacecloud')) {
	define('RAXSDK_SSL_VERIFYHOST', 0);
	define('RAXSDK_SSL_VERIFYPEER', 0);
}
define('INSTANCENAME', 'SmokeTestInstance');
define('SERVERNAME', 'SmokeTestServer');
define('NETWORKNAME', 'SMOKETEST');
define('MYREGION', $_ENV['OS_REGION_NAME']);
define('VOLUMENAME', 'SmokeTestVolume');
define('VOLUMESIZE', 103);
define('LBNAME', 'SmokeTestLoadBalancer');
define('CACHEFILE', '/tmp/smoketest.credentials');
define('TESTDOMAIN', 'domain-'.time().'.info');
define('RAXSDK_STRICT_PROPERTY_CHECKS', false);

require_once 'lib/php-opencloud.php';


/**
 * Cloud DNS
 */


/**
 * Cloud Files
 */


/**
 * Cloud Load Balancers
 */


/**
 * Cloud Servers
 */
try {
	$USE_SERVERS=TRUE;
	step('Connect to Cloud Servers');
	$cloudservers = $rackspace->Compute('cloudServersOpenStack', MYREGION);
} catch (Exception $e) {
	if (get_class($e) == 'OpenCloud\EndpointError')
		$USE_SERVERS=FALSE;
}

if ($USE_SERVERS) {
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
		if (!isset($centos) && isset($i->metadata->os_distro) &&
			 $i->metadata->os_distro == 'centos') {
			$centos = $i;
		}
	}

	step('Create Network');
	$network = $cloudservers->Network();
	//$network->Create(array('label'=>NETWORKNAME, 'cidr'=>'192.168.0.0/24'));

	step('List Networks');
	$netlist = $cloudservers->NetworkList();
	$netlist->Sort('label');
	while($net = $netlist->Next())
		info('%s: %s (%s)', $net->id, $net->label, $net->cidr);

	step('Connect to the VolumeService');
	$cbs = $rackspace->VolumeService('cloudBlockStorage', MYREGION);

	step('Volume Types');
	$list = $cbs->VolumeTypeList();
	$savedId = NULL;
	while($vtype = $list->Next()) {
		info('%s - %s', $vtype->id, $vtype->name);
		// save the ID for later
		if (!$savedId)
			$savedId = $vtype->id;
	}

	step('Create a new Volume');
	$volume = $cbs->Volume();
	$volume->Create(array(
		'display_name' => VOLUMENAME,
		'display_description' => 'A sample volume for testing',
		'size' => VOLUMESIZE,
		'volume_type' => $cbs->VolumeType($savedId)
	));
	$volume = $cbs->Volume($volume->id);

	step('Listing volumes');
	$list = $cbs->VolumeList();
	while($vol = $list->Next()) {
		info('Volume: %s %s [%s] size=%d',
			$vol->id,
			$vol->display_name,
			$vol->display_description,
			$vol->size);
	}

	step('Create Server');
	$server = $cloudservers->Server();

    $server->addFile('/var/test1', 'TEST 1');
    $server->addFile('/var/test2', 'TEST 2');

    $server->create(array(
        'name'     => 'SMOKETEST',
        'image'    => $centos,
        'flavor'   => $flavorlist->First(),
        'networks' => array(
            $cloudservers->Network(RAX_PUBLIC), 
            $cloudservers->Network(RAX_PRIVATE)
        ),
        "OS-DCF:diskConfig" => "AUTO"
    ));
    
	$ADMINPASSWORD = $server->adminPass;

	step('Wait for Server create');
	$server->WaitFor('ACTIVE', 600, 'dotter');

	// check for error
	if ($server->Status() == 'ERROR')
		die("Server create failed with ERROR\n");

	// test rebuild
	step('Rebuild the server');
	$server->Rebuild(array(
		'adminPass'=>$ADMINPASSWORD,
		'image'=>$centos
	));

	step('Wait for Server rebuild');
	$server->WaitFor('ACTIVE', 600, 'dotter');

	// check for error
	if ($server->Status() == 'ERROR')
		die("Server rebuild failed with ERROR\n");

	step('Attach the volume');
	$server->AttachVolume($volume);
	$volume->WaitFor('in-use', 600, 'dotter');

	step('Update the server name');
	$server->Update(array('name'=>SERVERNAME));
	$server->WaitFor('ACTIVE', 300, 'dotter');

	step('Reboot Server');
	$server->Reboot();
	$server->WaitFor('ACTIVE', 300, 'dotter');

	step('List Servers');
	$list = $cloudservers->ServerList();
	$list->Sort('name');
	while($s = $list->Next())
		info($s->name);

	step('Listing the server volume attachments');
	$list = $server->VolumeAttachmentList();
	while($vol = $list->Next())
		info('%s %-20s', $vol->id, $vol->Name());
	//exit;

	step('Detaching the volume');
	$server->DetachVolume($volume);
	$volume->WaitFor('available', 600, 'dotter');

	step('Creating a snapshot');
	$snap = $cbs->Snapshot();   // empty snapshot object
	$snap->Create(array(
		'display_name' => 'Smoketest Snapshot',
		'volume_id' => $volume->id
	));

	step('Deleting the test server(s)');
	$list = $cloudservers->ServerList();
	while($s = $list->Next()) {
		if ($s->name == SERVERNAME) {
			info('Deleting %s', $s->id);
			$s->Delete();
		}
	}
}
/**
 * Cloud Databases
 */
step('Connect to Cloud Databases');
$dbaas = $rackspace->DbService('cloudDatabases', MYREGION, 'publicURL');

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
$instance->WaitFor('ACTIVE', 600, 'dotter');

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
do {
	while($dbitem = $dblist->Next())
		info('Database Instance: %s (id %s)', $dbitem->name, $dbitem->id);
} while ($dblist = $dblist->NextPage());

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

/**
 * Cleanup
 */
step('Deleting the test network(s)');
$list = $cloudservers->NetworkList();
while($network = $list->Next()) {
	if ($network->label == NETWORKNAME) {
		info('Deleting: %s %s', $network->id, $network->label);
		$network->Delete();
	}
}

step('FINISHED at %s in %d seconds', date(TIMEFORMAT), time()-$start);
info('Remember to manually delete the volume and snapshot created');
exit();

/**
 * Callback for the WaitFor() method
 */
function dotter($obj) {
    info('...waiting on %s/%-12s %4s',
		$obj->Name(),
		$obj->Status(),
		isset($obj->progress) ? $obj->progress.'%' : 0);
}

