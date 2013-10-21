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

use OpenCloud\Common\Service\AbstractService;
use Guzzle\Http\Url;

/**
 * Description of AbstractContainer
 * 
 * @link 
 */
abstract class AbstractContainer extends AbstractResource
{
    const HEADER_OBJECT_COUNT = 'Object-Count';
    const HEADER_BYTES_USED   = 'Bytes-Used';
    const HEADER_ACCESS_LOGS  = 'Access-Log-Delivery';
    
    protected $metadataClass = 'OpenCloud\\ObjectStore\\Resource\\ContainerMetadata';
    
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

        $this->service = $service;

        // Populate data if set
        $this->populate($cdata);
    }
    
    public function getService()
    {
        return $this->service;
    }
    
    public function getCDNService()
    {
        return $this->service->getCDNService();
    }
    
    public function getClient()
    {
        return $this->getService()->getClient();
    }
    
    public function getTransId()
    {
        return $this->metadata->getProperty('X-Trans-Id');
    }
    
    public function isCdnEnabled()
    {
        if ($this instanceof CDNContainer) {
            return $this->metadata->getProperty('X-Cdn-Enabled') == 'True';
        } else {
            return empty($this->cdn);
        }
    }
    
    public function hasLogRetention()
    {
        if ($this instanceof CDNContainer) {
            return $this->metadata->getProperty('X-Log-Retention') == 'True';
        } else {
            return $this->metadata->getProperty(self::HEADER_ACCESS_LOGS);
        }
    }
    
    public function primaryKeyField()
    {
        return 'name';
    }
    
    public function update()
    {
        $this->getClient()
            ->post($this->getUrl(), $this->metadataHeaders())
            ->send();
        
        return true;
    }
    
    public function getUrl($path = null, array $params = array())
    {
        if (strlen($this->name) == 0) {
            throw new Exceptions\NoNameError(
            	Lang::translate('Container does not have an identifier')
            );
        }
        
        $url = $this->getService()->url(rawurlencode($this->name));
        
        if (!$url instanceof Url) {
            $url = Url::factory($url);
        }
        
        return $url;
    }
    
    protected function createRefreshRequest($name)
    {
        return $this->getClient()
            ->head($this->getUrl($name), array('Accept' => '*/*'))
            ->setExceptionHandler(array(
                404 => 'Container not found'
            ));
    }
    
    public function refresh($name = null, $url = null)
    {
        $response = $this->createRefreshRequest($name)->send();

		$headers = $response->getHeaders();
        $this->stockFromHeaders($headers);
        
        return $headers;  
    }
    
    public function stockFromHeaders($headers)
    {
	    $this->objectCount = $headers['X-Container-Object-Count'];
        $this->bytesUsed   = $headers['X-Container-Bytes-Used'];
        
        unset($headers['X-Container-Object-Count'], $headers['X-Container-Bytes-Used']);
        
        $this->setMetadata($headers, true);
    }
    
}