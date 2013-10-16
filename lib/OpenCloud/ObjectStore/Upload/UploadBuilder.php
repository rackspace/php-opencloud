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

/**
 * Description of UploadManager
 * 
 * @link 
 */
class UploadBuilder
{  
    protected $container;
    
    protected $entityBody;
    
    protected $options = array();
    
    public static function factory()
    {
        return new self();
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
    
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }
    
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }
    
    public function setEntityBody(EntityBody $entityBody)
    {
        $this->entityBody = $entityBody;
        return $this;
    }
    
    public function build()
    {
        // Validate properties
        if (!$this->container || !$this->entityBody || !$this->options['objectName']) {
            throw new InvalidArgumentError('A container, entity body and object name must be set');
        }
        
        // Create TransferState object for later use
        $transferState = TransferState::factory();
        
        // Bring in necessary options
        $this->options = array_merge($this->options, array(
            'containerName' => $this->container->getName(),
            'containerUri'  => $this->container->getUrl()
        ));
        
        // Instantiate Concurrent-/ConsecutiveTransfer 
        $transferClass = isset($this->options['concurrency']) && $this->options['concurrency'] > 1 
            ? __NAMESPACE__ . '\\ConcurrentTransfer' 
            : __NAMESPACE__ . '\\ConsecutiveTransfer';
        
        return $transferClass::newInstance()
            ->setClient($this->container->getClient())
            ->setEntityBody($this->entityBody)
            ->setTransferState($transferState)
            ->setOptions($this->options)
            ->setup();
    }
    
}