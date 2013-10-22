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
use OpenCloud\Common\Service\AbstractService;

/**
 * Abstract base class which implements shared functionality of ObjectStore 
 * resources. Provides support, for example, for metadata-handling and other 
 * features that are common to the ObjectStore components.
 */
abstract class AbstractResource extends Base
{
    const GLOBAL_METADATA_PREFIX = 'X';
    
    protected $metadata;
    protected $metadataClass = 'OpenCloud\\Common\\Metadata';
    
    /**
     * @var AbstractService The service object.
     */
    protected $service;
    
    public function  __construct(AbstractService $service)
    {
        $this->service  = $service;
        $this->metadata = new $this->metadataClass;
    }
    
    public function getService()
    {
        return $this->service;
    }
    
    public function getCdnService()
    {
        return $this->service->getCDNService();
    }
    
    public function getClient()
    {
        return $this->service->getClient();
    }
    
    public static function fromResponse(Response $response, AbstractService $service)
    {
        $object = new static($service);
        
        if (null !== ($headers = $response->getHeaders())) {
            $object->setMetadata($headers, true);
        }
        
        return $object;
    }
    
    public static function trimHeaders($headers)
    {
        $output = array();
        
        foreach ($headers as $header => $value) {
            // Only allow allow X-* headers to pass through after stripping them
            if (preg_match('#^' . self::GLOBAL_METADATA_PREFIX . '\-#i', $header)
                && ($key = self::stripPrefix($header))
            ) {
                $output[$key] = (string) $value;
            }
        }

        return $output;
    }
    
    private static function stripPrefix($header)
    {
        $pattern = '#^' . self::GLOBAL_METADATA_PREFIX . '\-(' . static::METADATA_LABEL . '-)?(Meta-)?#i';
        return preg_replace($pattern, '', $header);
    }
    
    public static function stockHeaders(array $headers)
    {
        $output = array();
        foreach ($headers as $header => $value) {
            $prefix = self::GLOBAL_METADATA_PREFIX . '-' . static::METADATA_LABEL . '-Meta-';
            $output[$prefix . $header] = $value;
        }
        return $output;
    }

    public function setMetadata($data, $constructFromResponse = false)
    {
        if ($constructFromResponse) {
            $metadata = new $this->metadataClass;
            $metadata->setArray(self::trimHeaders($data));
            $data = $metadata;
        }

        $this->metadata = $data;
        return $this;
    }
    
    public function getMetadata()
    {
        return $this->metadata;
    }
    
    public function saveMetadata(array $metadata)
    {
        $headers = self::stockHeaders($metadata);
        return $this->getClient()->post($this->getUrl(), $headers)->send();
    }
    
    public function retrieveMetadata()
    {
        $response = $this->getClient()
            ->head($this->getUrl())
            ->send();
        
        $this->setMetadata($response->getHeaders(), true);
        return $this->metadata;
    }
    
    public function unsetMetadataItem($key)
    {
        $header = sprintf('%s-Remove-%s-Meta-%s', self::GLOBAL_METADATA_PREFIX, 
            static::METADATA_LABEL, $key);
        
        return $this->getClient()
            ->post($this->getUrl(), array($header => 'True'))
            ->send();
    }
    
    public function appendToMetadata(array $values)
    {
        return (!empty($this->metadata) && is_array($this->metadata)) 
            ? array_merge($this->metadata, $values)
            : $values;
    }
    
}
