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
use OpenCloud\Common\Metadata;
use OpenCloud\Common\Exceptions\NameError;
use OpenCloud\Common\Exceptions\MetadataPrefixError;
use OpenCloud\Common\Http\Message\Response;

/**
 * Abstract base class which implements shared functionality of ObjectStore 
 * resources. Provides support, for example, for metadata-handling and other 
 * features that are common to the ObjectStore components.
 */
abstract class AbstractResource extends Base
{
    protected $metadata;

    public static function fromResponse(Response $response)
    {
        $object = new static();
        
        if ($body = $response->getDecodedBody()) {
            
        }
        
        if ($headers = $response->getHeaders()) {
            $object->setMetadata($headers, true);
        }
        
        return $object;
    }
    
    public static function trimHeaders(array $headers)
    {
        $output = array();
        foreach ($headers as $header => $value) {
            if (strpos($header, self::HEADER_METADATA_PREFIX) !== false) {
                $output[str_replace(self::HEADER_METADATA_PREFIX, '', $header)] = $value;
            }
        }
        return $output;
    }
    
    public static function stockHeaders(array $headers)
    {
        foreach ($headers as $header => $value) {
            $prefix = (!isset($value)) 
                ? self::HEADER_METADATA_UNSET_PREFIX 
                : self::HEADER_METADATA_PREFIX;
            $headers[$prefix . $header] = $value;
        }
        
        return $headers;
    }

    public function setMetadata($data, $constructFromResponse = false)
    {
        if ($constructFromResponse) {
            $metadata = new Metadata;
            $metadata->setArray(self::trimHeaders($data));
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
        $response = $this->getClient()->put($this->getUri(), $headers);
        
        $this->setMetdata($response->getHeaders(), true);
        return $this->getMetadata();
    }
    
    public function retrieveMetadata()
    {
        $response = $this->getClient()
            ->head($this->getUri())
            ->send();
        
        $this->setMetdata($response->getHeaders(), true);
        return $this->getMetadata();
    }
    
    protected function parameterizeCollectionUri($path = null, array $params = array())
    {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        } elseif ($params['format'] === false) {
            unset($params['format']);
        } elseif ($params['format'] != 'json') {
            throw new InvalidArgumentError('Invalid format type: can only be json');
        }
        
        return $this->getUri($path, $params);
    }
    
}
