<?php

/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */
namespace OpenCloud\ObjectStore\Resource;

/**
 * Description of Account
 * 
 * @link 
 */
class Account extends AbstractResource
{
    const HEADER_METADATA_PREFIX = 'X-Account-';
    const HEADER_BYTES_USED      = 'Bytes-Used';
    const HEADER_CONTAINER_COUNT = 'Container-Count';
    
    public function getDetails()
    {
        return $this->retrieveMetadata();
    }
    
    public function getObjectCount()
    {
        return $this->metadata->getProperty('Object-Count');
    }
    
    public function getBytesUsed()
    {
        return $this->metadata->getProperty('Bytes-Used');
    }
    
}