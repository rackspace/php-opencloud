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

use OpenCloud\Common\Metadata;

class ContainerMetadata extends Metadata
{
    public function getObjectCount()
    {
        return $this->objectCount;
    }
    
    public function getBytesUsed()
    {
        return $this->bytesUsed;
    }
    
    public function getCountQuota()
    {
        return $this->countQuota;
    }
    
    public function getBytesQuota()
    {
        return $this->bytesQuota;
    }
}