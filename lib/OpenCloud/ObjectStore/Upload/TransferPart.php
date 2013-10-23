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

use OpenCloud\Common\Http\Message\Response;
use Guzzle\Http\Url;

/**
 * @codeCoverageIgnore
 */
class TransferPart
{
    /**
     * @var int Its position in the upload queue.
     */
    protected $partNumber;
    
    /**
     * @var string This upload's ETag checksum.
     */
    protected $eTag;
    
    /**
     * @var int The length of this upload in bytes.
     */
    protected $contentLength;
    
    /**
     * @var string The API path of this upload.
     */
    protected $path;
    
    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;
        return $this;
    }
    
    public function getContentLength()
    {
        return $this->contentLength;
    }
    
    public function setETag($etag)
    {
        $this->etag = $etag;
        return $this;
    }
    
    public function getETag()
    {
        return $this->etag;
    }
    
    public function setPartNumber($partNumber)
    {
        $this->partNumber = $partNumber;
        return $this;
    }
    
    public function getPartNumber()
    {
        return $this->partNumber;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Create the request needed for this upload to the API.
     * 
     * @param EntityBody $part    The entity body being uploaded
     * @param int        $number  Its number/position, needed for name
     * @param OpenStack  $client  Client responsible for issuing requests
     * @param array      $options Set by the Transfer object
     * @return OpenCloud\Common\Http\Request
     */
    public static function createRequest($part, $number, $client, $options)
    {
        $name = sprintf('%s/%s/%d', $options['objectName'], $options['prefix'], $number);
        $url  = clone $options['containerUrl'];
        $url->addPath($name);

        $headers = array(
            'Content-Length' => $part->getContentLength(),
            'Content-Type'   => $part->getContentType()
		);
        
        if ($options['doPartChecksum'] === true) {
            $headers['ETag'] = $part->getContentMd5();
        }
        
        $request = $client->put($url, $headers, $part);
        
        if (isset($options['progress'])) {
            $request->getCurlOptions()->add('progress', true);
            if (is_callable($options['progress'])) {
	            $request->getCurlOptions()->add('progressCallback', $options['progress']);
            }
        }

        return $request;
    }
    
    public static function fromResponse(Response $response, $partNumber = 1)
    {
        $responseUri = Url::factory($response->getEffectiveUrl());
        
        $object = new self();
        
        $object->setPartNumber($partNumber)
            ->setContentLength($response->getHeader('Content-Length'))
            ->setETag($response->getHeader('ETag'))
            ->setPath($responseUri->getPath());
        
        return $object;
    }
    
}
