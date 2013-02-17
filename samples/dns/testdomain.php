<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('rackspace.php');

define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

// establish our credentials
$cloud = new OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

// connect to the DNS service
$dns = $cloud->DNS();

// get a domain
$domain = $dns->Domain(array(
	'name' => 'raxdrg.info',
	'ttl' => 3600,
	'emailAddress' => 'glen@xlerb.com'));
$domain->AddRecord($domain->Record(array(
	'type' => 'A',
	'name' => 'raxdrg.info',
	'ttl' => 600,
	'data' => '50.56.174.152')));
$domain->AddRecord($domain->Record(array(
	'type' => 'AAAA',
	'name' => 'raxdrg.info',
	'ttl' => 600,
	'data' => '2001:4800:780e:0510:a325:deec:ff04:48a8')));
$domain->AddRecord($domain->Record(array(
	'type' => 'MX',
	'name' => 'raxdrg.info',
	'ttl' => 600,
	'data' => 'mx1.xlerb.com',
	'priority' => 10)));
$domain->AddRecord($domain->Record(array(
	'type' => 'MX',
	'name' => 'raxdrg.info',
	'ttl' => 600,
	'data' => 'mx2.xlerb.com',
	'priority' => 20)));
$domain->AddRecord($domain->Record(array(
	'type' => 'CNAME',
	'name' => 'www.raxdrg.info',
	'ttl' => 600,
	'data' => 'rack2.broadpool.net',
	'comment' => 'Added '.date('%Y-%m-%d %H:%I:%S'))));
//setDebug(True);
$domain->Create();
setDebug(False);