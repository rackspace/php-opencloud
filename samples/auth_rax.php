<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;

require_once('rackspace.inc');
require_once('compute.inc');

// my credentials
define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
$mysecret = array(
    'username' => '{username}',
    'apiKey' => '{apiKey}'
);

// establish our credentials
$connection = new Rackspace(AUTHURL, $mysecret);
