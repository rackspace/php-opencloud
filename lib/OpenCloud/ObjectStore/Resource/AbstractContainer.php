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
 * Description of AbstractContainer
 * 
 * @link 
 */
abstract class AbstractContainer
{
    const HEADER_OBJECT_COUNT = 'X-Container-Object-Count';
    const HEADER_BYTES_USED = 'X-Container-Bytes-Used';
    
    /**
     * The name of the container. 
     * 
     * The only restrictions on container names is that they cannot contain a 
     * forward slash (/) and must be less than 256 bytes in length. Please note 
     * that the length restriction applies to the name after it has been URL 
     * encoded. For example, a container named Course Docs would be URL encoded
     * as Course%20Docs - which is 13 bytes in length rather than the expected 11.
     * 
     * @var string 
     */
    public $name;
    
    /**
     * @var AbstractService The service object.
     */
    private $service;
    
    protected $objectCount = 0;
    
    protected $bytesUsed = 0;
    
    public function __construct(AbstractService $service, $cdata = null)
    {
        $this->getLogger()->info('Initializing CDN Container Service...');

        parent::__construct();

        $this->service = $service;

        // Populate data if set
        $this->populate($cdata);
    }
    
    public function getService()
    {
        return $this->service;
    }
    
    public function getClient()
    {
        return $this->getService()->getClient();
    }
    
    public function getObjectCount($retrieve = false)
    {
        return $this->getMetadata()->getObjectCount();
    }
    
    public function getBytesUsed($retrieve = false)
    {
        return $this->getMetadata()->getBytesUsed();
    }
    
    public function getQuota($type = 'bytes') 
    {
        if ($type == 'bytes') {
            return $this->metadata->getBytesQuota();
        } elseif ($type == 'count') {
            return $this->metadata->getCountQuota();
        } else {
            throw new InvalidArgumentError('Please specify a type of quota: either bytes or count.');
        }
    }
    
    public function primaryKeyField()
    {
        return 'name';
    }
    
    public function update()
    {
        $this->getClient()
            ->post($this->getUri(), $this->metadataHeaders())
            ->send();
        
        return true;
    }
    
}