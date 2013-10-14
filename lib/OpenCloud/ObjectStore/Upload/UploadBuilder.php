<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Upload;

use OpenCloud\Common\Exceptions\InvalidArgumentError;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Url;

/**
 * Description of UploadManager
 * 
 * @link 
 */
class UploadBuilder
{ 
    protected $partSize;
    
    protected $concurrency;
    
    protected $container;
    
    protected $objectName;
    
    protected $entityData;
    
    protected $options;
    
    public static function factory()
    {
        return new self();
    }
    
    public function setPartSize($partSize)
    {
        $this->partSize = $partSize;
        return $this;
    }
    
    public function setConcurrency($concurrency)
    {
        $this->concurrency = $concurrency;
        return $this;
    }
    
    /**
     * @param type $options Available configuration options:
     * 
     * * `concurrency'    <bool>   The number of concurrent workers.
     * * `partSize'       <int>    The size, in bytes, for the chunk
     * * `doPartChecksum' <bool>   Enable or disable MD5 checksum in request (ETag)
     * * `prefix'         <string> The prefix that will be used. The format is:
     * 
     * <MAIN_OBJECT_NAME>_<PREFIX>_<SEGMENT_NUMBER>
     * 
     * The default is `segment'. For example, if you are uploading FooBar.iso,
     * its chunks will have the following naming structure:
     * 
     * FooBar_segment_1
     * FooBar_segment_2
     * FooBar_segment_3
     * 
     * @return \OpenCloud\ObjectStore\Upload\UploadBuilder
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
    
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }
    
    public function setObjectName($objectName)
    {
        $this->objectName = $objectName;
        return $this;
    }
    
    public function setEntityData($data)
    {
        // Handle string pathnames and other types that can be converted
        if (is_string($data)) {
            if (!file_exists($data)) {
                throw new InvalidArgumentError(
                    'If a string is provided for the entity data, it must reference a valid pathname.'
                );
            }
            clearstatcache(true, $data);
            $data = fopen($data, 'r');
        }
        
        $this->entityData = EntityBody::factory($data);
        
        return $this;
    }
    
    public function build()
    {
        // Validate properties
        if (!$this->container || !$this->entityData || !$this->objectName) {
            throw new InvalidArgumentError('A container, entity body and object name must be set');
        }
        
        // Create TransferState object for later use
        $this->transferState = new TransferState();
        var_dump($this->container->getUrl());die;
        // Bring in necessary options
        $this->options = array_merge($this->options, array(
            'containerUri' => $this->container->getUrl(),
            'objectName'   => $this->objectName,
            'concurrency'  => $this->concurrency
        ));
        
        // Instantiate Concurrent-/ConsecutiveTransfer 
        $transferClass = $this->concurrency > 1 
            ? 'ConcurrentTransfer' 
            : 'ConsecutiveTransfer';
        
        return $transferClass::factory()
            ->setClient($this->container->getClient())
            ->setEntityData($this->entityData)
            ->setTransferState($this->transferState)
            ->setOptions($this->options)
            ->setup();
    }
    
}