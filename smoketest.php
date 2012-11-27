<?php
/**
 * (c)2012 Rackspace Hosting. See COPYING for license details
 *
 * The purpose of this smoketest is simply to ensure that the core
 * functionality of the library is present. It is not an exhaustive
 * integration test, nor is it a unit test. The goal is to rapidly
 * identify major problems if a code change breaks something.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
$start = time();
ini_set('include_path', './lib:'.ini_get('include_path'));
if (strpos($_ENV['NOVA_URL'], 'staging.identity.api.rackspacecloud')) {
	define('RAXSDK_SSL_VERIFYHOST', 0);
	define('RAXSDK_SSL_VERIFYPEER', 0);
}
require('rackspace.inc');
define('INSTANCENAME', 'SmokeTestInstance');
define('SERVERNAME', 'SmokeTestServer');
define('NETWORKNAME', 'SMOKETEST');
define('MYREGION', $_ENV['OS_REGION_NAME']);
define('VOLUMENAME', 'SmokeTestVolume');
define('VOLUMESIZE', 103);

/**
 * Relies upon environment variable settings — these are the same environment
 * variables that are used by python-novaclient. Just make sure that they're
 * set to the right values before running this test.
 */
define('AUTHURL', $_ENV['NOVA_URL']);
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
function info($msg,$p1=NULL,$p2=NULL,$p3=NULL,$p4=NULL,$p5=NULL) {
    printf("  %s\n", sprintf($msg,$p1,$p2,$p3,$p4,$p5));
}
define('TIMEFORMAT', 'r');

/**
 * START THE TESTS!
 */
printf("SmokeTest started at %s\n", date(TIMEFORMAT, $start));
printf("Using endpoint [%s]\n", $_ENV['NOVA_URL']);
printf("Using region [%s]\n", MYREGION);

step('Authenticate');
$rackspace = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY ));
$rackspace->AppendUserAgent('(PHP SDK SMOKETEST)');

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
	$network->Create(array('label'=>NETWORKNAME, 'cidr'=>'192.168.0.0/24'));
	
	step('List Networks');
	$netlist = $cloudservers->NetworkList();
	$netlist->Sort('label');
	while($net = $netlist->Next())
		info('%s: %s (%s)', $net->id, $net->label, $net->cidr);
	
	step('Connect to the VolumeService');
	$cbs = $rackspace->VolumeService('cloudBlockStorage', MYREGION);
	
	step('Volume Types');
	$list = $cbs->VolumeTypeList();
	while($vtype = $list->Next()) {
		info('%s - %s', $vtype->id, $vtype->name);
	}
	
	step('Create a new Volume');
	$volume = $cbs->Volume();
	//setDebug(TRUE);
	$volume->Create(array(
		'display_name' => VOLUMENAME,
		'display_description' => 'A sample volume for testing',
		'size' => VOLUMESIZE,
		'volume_type' => $cbs->VolumeType(2)
	));
	$volume = $cbs->Volume($volume->id);
	setDebug(FALSE);
	
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
	$server->Create(array(
		'name'=>'FOOBAR',
		'image'=>$centos,
		'flavor'=>$flavorlist->First(),
		'networks'=>array($network, $cloudservers->Network(RAX_PUBLIC))
	));
	
	step('Wait for Server create');
	$server->WaitFor('ACTIVE', 600, 'dotter');
	
	// check for error
	if ($server->Status() == 'ERROR')
		die("Server create failed with ERROR\n");
	
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
	//setDebug(TRUE);
	$list = $server->VolumeAttachmentList();
	while($vol = $list->Next())
		info('%s %-20s', $vol->id, $vol->Name());
	setDebug(FALSE);
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

/**
 * Cloud Files
 */
step('Connect to Cloud Files');
$cloudfiles = $rackspace->ObjectStore('cloudFiles', MYREGION);

step('Create Container');
$container = $cloudfiles->Container();
$container->Create(array('name' => 'SmokeTestContainer'));

step('Publish Container to CDN');
$container->PublishToCDN(60); // 60-second TTL
info('CDN URL:    %s', $container->CDNUrl());
info('Public URL: %s', $container->PublicURL());

step('Create Object from this file');
$object = $container->DataObject();
$object->Create(
	array('name'=>'SmokeTestObject','content_type'=>'text/plain'), __FILE__);
info('Public URL:    %s', $object->PublicURL());
info('SSL URL:       %s', $object->PublicURL('SSL'));
info('Streaming URL: %s', $object->PublicURL('Streaming'));

step('Copy Object');
$target = $container->DataObject();
$target->name = 'COPY-of-SmokeTestObject';
$object->Copy($target);

step('List Containers');
//setDebug(TRUE);
$list = $cloudfiles->ContainerList();
//setDebug(FALSE);
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
    info('...waiting on %s/%-12s %3d%%',
		$obj->Name(),
		$obj->Status(),
		isset($obj->progress) ? $obj->progress : 0);
}
