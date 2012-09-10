<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

/**
 * This sample illustrates how to create a Swift/CloudFiles object
 * from a file using the file_get_contents() function. It uses its
 * own source code as the data!
 */

namespace OpenCloud;

define('RAXSDK_OBJSTORE_NAME','cloudFiles');
define('RAXSDK_OBJSTORE_REGION','DFW');
require_once('rackspace.inc');
require_once('objectstore.inc');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$connection = new Rackspace(AUTHURL,
	array('username' => USERNAME,
		   'tenantName' => TENANT,
		   'apiKey' => APIKEY));

// create a Cloud Files (ObjectStore) connection
$ostore = $connection->ObjectStore(/* uses defaults from above */);

// next, make a container named 'Sample'
$cont = $ostore->Container();
$cont->Create('Sample');

// finally, create an object in that container named hello.txt
$obj = $cont->Object();
// read this file!
$obj->Create(array('name' => 'SampleObject', 'type' => 'text/plain'), __FILE__);

// list all the objects in the container
$list = $cont->ObjectList();
while($o = $list->Next())
	printf("Object: %s size: %d type: %s\n",
	    $o->name, $o->bytes, $o->content_type);
