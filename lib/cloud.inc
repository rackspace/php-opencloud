<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

/**
 * This file is provided as a convenience in case you want to use multiple
 * cloud services. Instead of including each service file individually, this
 * file loads them all at once.
 *
 * Example:
 * <code>
 * require('cloud.inc');
 * </code>
 *
 * Since Rackspace is a subclass of OpenStack, you can use either class
 * for your connection object.
 */

require_once('rackspace.inc');
require_once('compute.inc');
require_once('objectstore.inc');
