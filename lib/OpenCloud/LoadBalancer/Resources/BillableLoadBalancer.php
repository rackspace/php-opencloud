<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\LoadBalancer\Resources;

/**
 * Used to get a list of billable load balancers for a specific date range
 */
class BillableLoadBalancer extends ReadOnly 
{

    protected static $url_resource = 'loadbalancers/billable';
    protected static $json_name = null;
}
