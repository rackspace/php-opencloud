<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

use Guzzle\Http\EntityBody;
use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Exceptions\InvalidArgumentError;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\ObjectStore\Constants\UrlType;

/**
 * The ObjectStore (Cloud Files) service.
 */
class Service extends AbstractService 
{
    
    const DEFAULT_NAME = 'cloudFiles';
    
    /**
     * This holds the associated CDN service (for Rackspace public cloud)
     * or is NULL otherwise. The existence of an object here is
     * indicative that the CDN service is available.
     */
    private $cdnService;
    
    private $tempUrlSecret;

    public function __construct(
        OpenStack $connection,
        $serviceName = RAXSDK_OBJSTORE_NAME,
        $serviceRegion = RAXSDK_OBJSTORE_REGION,
        $urltype = RAXSDK_OBJSTORE_URLTYPE
    ) {
        $this->getLogger()->info('Initializing Container Service...');

        parent::__construct(
            $connection,
            'object-store',
            $serviceName,
            $serviceRegion,
            $urltype
        );

        // establish the CDN container, if available
        try {
            $this->cdnService = new CDNService(
                $connection,
                $serviceName . 'CDN',
                $serviceRegion,
                $urltype
            );
        } catch (Exceptions\EndpointError $e) {}
    }

    /** 
     * Sets the shared secret value for the TEMP_URL
     *
     * @param string $secret the shared secret
     * @return HttpResponse
     */
    public function setTempUrlSecret($secret = null) 
    {
        if (!$secret) {
            $secret = sha1(rand(1, 99999));
        }
        
        $this->tempUrlSecret = $secret;
        
        return $this->getClient()->post($this->getUrl(), array(
            'X-Account-Meta-Temp-Url-Key' => $secret
        ));
    }
    
    public function getTempUrlSecret()
    {
        return $this->tempUrlSecret;
    }

    public function getCdnService() 
    {
        return $this->cdnService;
    }
    
    public function getContainer($data = null)
    {
        return new Container($this, $data);
    }
    
    public function createContainer($name, array $metadata = array())
    {
        $this->checkContainerName($name);
        
        $containerHeaders = Container::stockHeaders($metadata);
            
        $response = $this->getClient()
            ->put($this->getUrl($name), $containerHeaders)
            ->send();
        
        if ($response->getStatusCode() == 201) {
            return Container::fromResponse($response, $this);
        }
        
        return false;
    }
    
    public function checkContainerName($name)
    {
        if (strlen($name) == 0) {
            $error = 'Container name cannot be blank';
        }

        if (strpos($name, '/') !== false) {
            $error = 'Container name cannot contain "/"';
        }

        if (strlen($name) > self::MAX_CONTAINER_NAME_LENGTH) {
            $error = 'Container name is too long';
        }
        
        if (isset($error)) {
            throw new InvalidArgumentError($error);
        }

        return true;
    }
    
    public function bulkExtract($path, $archive, $archiveType = UrlType::TAR_GZ)
    {
        $entity = EntityBody::factory($archive);
        
        $acceptableTypes = array(
            UrlType::TAR,
            UrlType::TAR_GZ,
            UrlType::TAR_BZ2
        );
        
        if (!in_array($archiveType, $acceptableTypes)) {
            throw new Exceptions\InvalidArgumentError(sprintf(
                'The archive type must be one of the following: [%s]. You provided [%s].',
                implode($acceptableTypes, ','),
                print_r($archiveType, true)
            ));
        }
        
        $url = $this->getUrl()->addPath($path)->setQuery(array('extract-archive' => $archiveType));
        $response = $this->getClient()->put($url, array(), $entity)->send();
        
        $message = $response->getDecodedBody();

        if (!empty($message->Errors)) {
            throw new Exception\BulkOperationException((array) $message->Errors);
        }
        
        return $response;
    }
    
    public function bulkDelete(array $paths)
    {
        $entity = EntityBody::factory(implode(PHP_EOL, $paths));
        
        $url = $this->getUrl()->setQuery(array('bulk-delete' => true));
        
        $response = $this->getClient()
            ->delete($url, array('Content-Type' => 'text/plain'), $entity)
            ->send();
        
        $message = $response->getDecodedBody();
        
        if (!empty($message->Errors)) {
            throw new Exception\BulkOperationException((array) $message->Errors);
        }
        
        return $response;
    }
    
}