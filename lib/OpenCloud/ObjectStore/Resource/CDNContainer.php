<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Resource;

/**
 * A container that has been CDN-enabled. Each CDN-enabled container has a unique 
 * Uniform Resource Locator (URL) that can be combined with its object names and 
 * openly distributed in web pages, emails, or other applications.
 */
class CDNContainer extends AbstractContainer
{
    const HEADER_METADATA_PREFIX = 'X-Cdn-';
    const HEADER_METADATA_UNSET_PREFIX = 'X-Remove-Cdn-';
    
    public function getCdnSslUri()
    {
        return $this->metadata->getProperty('X-Cdn-Ssl-Uri');
    }

    public function getCdnUri()
    {
        return $this->metadata->getProperty('X-Cdn-Uri');
    }
    
    public function getTtl()
    {
        return $this->metadata->getProperty('X-Ttl');
    }
    
    public function getCdnStreamingUri()
    {
        return $this->metadata->getProperty('X-Cdn-Streaming-Uri');
    }
    
    public function purgeObjects()
    {
        
    }
    
}
