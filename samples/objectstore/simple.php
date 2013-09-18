<?php
require_once "php-opencloud.php";

// establish our credentials
$mycloud = new \OpenCloud\Rackspace(
	RACKSPACE_US,
	array('username' => 'raxglenc',
		  'apiKey' => $_ENV['NOVA_API_KEY']));

// create a Cloud Files (ObjectStore) connection
$objectstore = $mycloud->objectStore('cloudFiles', 'DFW', 'publicURL');

// make a container
$mycontainer = $objectstore->Container();
$mycontainer->create(array('name'=>'MyContainer'));

// publish it to the CDN
$mycontainer->publishToCDN(3600);	// cache for one hour

// create an object in that container named Hello
$myobject = $mycontainer->dataObject();
// read this file!
$myobject->create(
	array('name' => 'Hello.txt'),
	'./hello.txt');

// list all the containers in the object
$list = $mycontainer->objectList();
while($o = $list->Next()) {
	printf("Object: %s size: %d type: %s URL %s\n",
	    $o->name, $o->bytes, $o->content_type, $o->publicUrl());
}
