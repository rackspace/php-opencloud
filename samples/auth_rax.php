<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;

require_once('rackspace.php');
require_once('compute.php');

// my credentials
define('AUTHURL', RACKSPACE_US);
$mysecret = array(
    'username' => '{username}',
    'apiKey' => '{apiKey}'
);

// establish our credentials
$connection = new Rackspace(AUTHURL, $mysecret);
