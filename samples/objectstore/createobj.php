<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

/**
 * This sample illustrates how to create a Swift/CloudFiles object
 * from a file using the file_get_contents() function. It uses its
 * own source code as the data!
 */

define('RAXSDK_OBJSTORE_NAME','cloudFiles');
define('RAXSDK_OBJSTORE_REGION','DFW');
define('RAXSDK_OBJSTORE_URLTYPE', 'publicURL');

require_once "php-opencloud.php";

define('AUTHURL', RACKSPACE_US);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

define('TEMP_URL_SECRET', 'April is the cruellest month, breeding lilacs...');
define('OBJNAME', 'SampleObject');
define('CONTAINER_NAME', 'A Container Name With Spaces');

// progress callback function
function UploadProgress($len) {
	printf("[uploading %d bytes]", $len);
}

// establish our credentials
$connection = new \OpenCloud\Rackspace(AUTHURL,
	array('username' => USERNAME,
		   'apiKey' => APIKEY));

// set the callback function
$connection->SetUploadProgressCallback('UploadProgress');

// create a Cloud Files (ObjectStore) connection
$ostore = $connection->ObjectStore(/* uses defaults from above */);

//setDebug(TRUE);

// set the temp URL secret
$ostore->SetTempUrlSecret(TEMP_URL_SECRET);

// next, make a container named CONTAINER_NAME
printf("Creating container...\n");
$cont = $ostore->Container();
$cont->Create(array('name'=>CONTAINER_NAME));

// finally, create an object in that container named hello.txt
printf("Creating object...\n");
$obj = $cont->DataObject();
// read this file!
$obj->Create(
	array('name' => OBJNAME, 'content_type' => 'text/plain'),
	__FILE__);
$obj->metadata->something = 'This is a metadata value';
$obj->UpdateMetadata();
printf("\nObject hash is %s\n", $obj->hash);
printf("Object URL is %s\n", $obj->Url());
$temp = $obj->TempUrl(TEMP_URL_SECRET, 600, 'GET');
printf("TEMP_URL is %s\n", $temp);

// copy it to another object
printf("Copying...\n");
$target = $cont->DataObject();
$target->name = $obj->Name().'-COPY';
$obj->Copy($target);

// re-retrieve the object
$obj = $cont->DataObject(OBJNAME);
printf("Testing metadata:\n");
print_r($obj->metadata);

// list all the objects in the container
printf("Listing:\n");
$list = $cont->ObjectList();
while($o = $list->Next()) {
	printf("Object: %s size: %d type: %s\n",
	    $o->name, $o->bytes, $o->content_type);
	print_r($o->metadata);
}
