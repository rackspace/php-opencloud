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

namespace OpenCloud\DNS;

/**
 * The Subdomain is basically another domain, albeit one that is a child of
 * a parent domain. In terms of the code involved, the JSON is slightly
 * different than a top-level domain, and the parent is a domain instead of
 * the DNS service itself.
 */
class Subdomain extends Domain 
{

    protected static $json_name = false;
    protected static $json_collection_name = 'domains';
    protected static $url_resource = 'subdomains';

}
