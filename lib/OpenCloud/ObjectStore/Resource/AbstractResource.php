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

use OpenCloud\Common\Base;
use OpenCloud\Common\Http\Message\Response;

/**
 * Abstract base class which implements shared functionality of ObjectStore 
 * resources. Provides support, for example, for metadata-handling and other 
 * features that are common to the ObjectStore components.
 */
abstract class AbstractResource extends Base
{
    protected $metadata;
    protected $metadataClass = 'OpenCloud\\Common\\Metadata';

    public static function fromResponse(Response $response)
    {
        $object = new static();
        
        if (null !== ($headers = $response->getHeaders())) {
            $object->setMetadata($headers, true);
        }
        
        return $object;
    }
    
    public static function trimHeaders($headers)
    {
        $output = array();
        foreach ($headers as $header => $value) {
            $pattern = '#' . static::HEADER_METADATA_PREFIX . 'e#';
            if (preg_match($pattern, $header) !== false) {
                $key = str_replace(static::HEADER_METADATA_PREFIX, '', $header);
                $output[$key] = (string) $value;
            }
        }
        return $output;
    }
    
    public static function stockHeaders(array $headers)
    {
        foreach ($headers as $header => $value) {
            $prefix = (!isset($value)) 
                ? static::HEADER_METADATA_UNSET_PREFIX 
                : static::HEADER_METADATA_PREFIX;
            $headers[$prefix . $header] = $value;
        }
        
        return $headers;
    }

    public function setMetadata($data, $constructFromResponse = false)
    {
        if ($constructFromResponse) {
            $metadata = new $this->metadataClass;
            $metadata->setArray(self::trimHeaders($data));
            $data = $metadata;
        }

        $this->metadata = $data;
    }
    
    public function getMetadata()
    {
        if (null === $this->metadata) {
            $this->retrieveMetadata();
        }
        return $this->metadata;
    }
    
    public function saveMetadata(array $metadata)
    {
        $headers = self::stockHeaders($metadata);
        $response = $this->getClient()->put($this->getUrl(), $headers);
        
        $this->setMetdata($response->getHeaders(), true);
        return $this->getMetadata();
    }
    
    public function retrieveMetadata()
    {
        $response = $this->getClient()
            ->head($this->getUrl())
            ->send();
        
        $this->setMetdata($response->getHeaders(), true);
        return $this->metadata;
    }
    
}
