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

use OpenCloud\Common\Service\AbstractService as CommonAbstractService;
use OpenCloud\Common\Exceptions\InvalidArgumentError;
use OpenCloud\ObjectStore\Resource\Container;

define('SWIFT_MAX_OBJECT_SIZE', 5 * 1024 * 1024 * 1024 + 1);

/**
 * An abstract base class for common code shared between ObjectStore\Service
 * (container) and ObjectStore\CDNService (CDN containers).
 * 
 * @todo Maybe we use Traits instead of this small abstract class?
 */
abstract class AbstractService extends CommonAbstractService
{

    const MAX_CONTAINER_NAME_LENGTH = 256;
    const MAX_OBJECT_NAME_LEN       = 1024;
    const MAX_OBJECT_SIZE           = SWIFT_MAX_OBJECT_SIZE;
    
    
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
            return Container::fromResponse($response);
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
    
    public function listContainers(array $filter = array())
    {
        $uri = $this->parameterizeCollectionUri(null, $filter);
        return $this->resourceList('Container', $uri);
    }

}
