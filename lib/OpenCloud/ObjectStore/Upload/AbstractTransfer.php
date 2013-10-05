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

/**
 * Description of AbstractTransfer
 * 
 * @link 
 */
class AbstractTransfer
{
    const MIN_PART_SIZE = 1048576;
    const MAX_PART_SIZE = 5368709120;
    const DEFAULT_PART_SIZE = 5242880;
    
    protected $client;
    
    protected $entityBody;
    
    protected $transferState;
    
    protected $options;
    
    protected $defaultOptions = array(
        'concurrency' => true,
        'partSize'    => self::MIN_PART_SIZE,
        'prefix'      => 'segment',
        'doPartChecksum' => true
    );
    
    public static function factory()
    {
        return new self();
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
    
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }
    
    public function setup()
    {
        $this->options = array_replace($this->defaultOptions, $this->options);
        
        $this->partSize = $this->calculatePartSize();
    }
    
    protected function calculatePartSize()
    {
        // If no part-size is provided, use default
        $partSize = $this->options['partSize'] ?: self::DEFAULT_PART_SIZE;
        
        // Make sure it falls within a certain range
        $partSize = min($partSize, self::MAX_PART_SIZE);
        $partSize = max($partSize, self::MIN_PART_SIZE);

        return $partSize;
    }
    
    public function upload()
    {
        if ($this->transferState->isAborted()) {
            throw new RuntimeException('The transfer has been aborted.');
        }

        try {
            $this->transfer();
            $result = $this->complete();
        } catch (Exception $e) {
            throw new MultipartUploadException($this->transferState, $e);
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
            'X-Object-Manifest' => sprintf('%s_%s', $this->options['objectName'], $this->options['prefix'])
        );
        
        $body = null;
        
        if ($this->isStaticLargeObject) {
            $body = json_encode($parts);
            $headers['Content-Length'] = strlen($body);
        }
        
        $uri = $this->options['containerUri'] . '/' . $this->options['objectName'];
        
        $response = $this->getClient()->put($uri, $headers, $body)->send();
        
        return $response;
    }
    
}