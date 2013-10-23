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
use OpenCloud\Common\Exceptions;

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
    
    public function __construct(AbstractService $service, $data = null)
    {
        $this->service  = $service;
        $this->metadata = new $this->metadataClass;

        // Populate data if set
        $this->populate($data);
    }
    
    public function getTransId()
    {
        return $this->metadata->getProperty('Trans-Id');
    }
    
    public function isCdnEnabled()
    {
        if ($this instanceof CDNContainer) {
            return $this->metadata->getProperty('Enabled') == 'True';
        } else {
            return empty($this->cdn);
        }
    }
    
    public function hasLogRetention()
    {
        if ($this instanceof CDNContainer) {
            return $this->metadata->getProperty('Log-Retention') == 'True';
        } else {
            return $this->metadata->propertyExists(self::HEADER_ACCESS_LOGS);
        }
    }
    
    public function primaryKeyField()
    {
        return 'name';
    }

    public function getUrl($path = null, array $params = array())
    {
        if (strlen($this->getName()) == 0) {
            throw new Exceptions\NoNameError('Container does not have a name');
        }

        $url = $this->getService()->getUrl();
        return $url->addPath($this->getName())->addPath($path)->setQuery($params);
    }
    
    protected function createRefreshRequest()
    {
        return $this->getClient()
            ->head($this->getUrl(), array('Accept' => '*/*'))
            ->setExceptionHandler(array(
                404 => 'Container not found'
            ));
    }

}