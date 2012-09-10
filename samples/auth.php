<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('openstack.inc');

// my credentials
define('AUTHURL', '{your authorization URL}');
$mysecret = array(
    'username' => '{username}',
    'password' => '{password}'
);

// establish our credentials
$connection = new OpenCloud\OpenStack(AUTHURL, $mysecret);
