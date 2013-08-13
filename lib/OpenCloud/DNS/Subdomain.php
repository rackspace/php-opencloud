<?php

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
