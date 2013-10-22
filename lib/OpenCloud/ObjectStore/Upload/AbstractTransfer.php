<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Upload;

use Exception;
use OpenCloud\Common\Http\Client;
use Guzzle\Http\EntityBody;
use OpenCloud\Common\Exceptions\RuntimeException;
use OpenCloud\ObjectStore\Exception\UploadException;

/**
 * Description of AbstractTransfer
 * 
 * @link 
 */
class AbstractTransfer
{
    /**
     * Minimum chunk size is 1MB.
     */
    const MIN_PART_SIZE = 1048576;
    
    /**
     * Maximum chunk size is 5GB.
     */
    const MAX_PART_SIZE = 5368709120;
    
    /**
     * Default chunk size is 1GB.
     */
    const DEFAULT_PART_SIZE = 1073741824;
    
    protected $client;
    
    protected $entityBody;
    
    protected $transferState;
    
    protected $options;
    
    protected $defaultOptions = array(
        'concurrency'    => true,
        'partSize'       => self::DEFAULT_PART_SIZE,
        'prefix'         => 'segment',
        'doPartChecksum' => true
    );
    
    public static function newInstance()
    {
        return new static();
    }
    
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }
    
    public function setEntityBody(EntityBody $entityBody)
    {
        $this->entityBody = $entityBody;
        return $this;
    }
    
    public function setTransferState(TransferState $transferState)
    {
        $this->transferState = $transferState;
        return $this;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
    
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }
    
    public function setup()
    {
        $this->options  = array_merge($this->defaultOptions, $this->options);
        $this->partSize = $this->validatePartSize();
    }
    
    protected function validatePartSize()
    {
        // Make sure it falls within a certain range
        return max(
            min($this->options['partSize'], self::MAX_PART_SIZE), 
            self::MIN_PART_SIZE
        );
    }
    
    public function upload()
    {
        if (!$this->transferState->isRunning()) {
            throw new RuntimeException('The transfer has been aborted.');
        }

        try {
            $this->transfer();
            $result = $this->complete();
        } catch (Exception $e) {
            throw new UploadException($this->transferState, $e);
        }

        return $result;
    }
    
    protected function complete()
    {
        return $this->createManifest();
    }
    
    private function createManifest()
    {
        $parts = array();
        
        foreach ($this->transferState as $part) {
            $parts[] = (object) array(
                'path'       => $part->getPath(),
                'etag'       => $part->getETag(),
                'size_bytes' => $part->getContentLength()
            );
        }
        
        $headers = array(
            'Content-Length'    => 0,
            'X-Object-Manifest' => sprintf('%s/%s/%s/', 
                $this->options['containerName'],
                $this->options['objectName'], 
                $this->options['prefix']
            )
        );
        
        $url = clone $this->options['containerUrl'];
        $url->addPath($this->options['objectName']);
        
        $response = $this->client->put($url, $headers)->send();
        
        return $response;
    }
    
}