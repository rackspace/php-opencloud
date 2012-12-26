<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('rackspace.inc');
require_once('compute.inc');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$connection = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

// now, connect to the compute service
print "Connecting...\n";
$dbservice = $connection->DbService('cloudDatabases', 'DFW');

// delete all the instances created by the create.php script
$inlist = $dbservice->InstanceList();
while($instance = $inlist->Next()) {
    printf("Instance: %s (%s)\n", $instance->id, $instance->Name());
    $dblist = $instance->DatabaseList();
    while($db = $dblist->Next()) {
        printf("  Database: %s\n", $db->name);
    }
    $userlist = $instance->UserList();
    while($user = $userlist->Next()) {
        printf("  User: %s\n", $user->Name());
        foreach($user->databases as $db)
            printf("    Database for user; %s\n", $db->Name());
    }
}
