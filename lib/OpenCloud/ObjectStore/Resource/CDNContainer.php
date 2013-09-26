<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Resource;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\ObjectStore\AbstractService as AbstractObjectService;

/**
 * A container that has been CDN-enabled. Each CDN-enabled container has a unique 
 * Uniform Resource Locator (URL) that can be combined with its object names and 
 * openly distributed in web pages, emails, or other applications.
 */
class CDNContainer extends AbstractStorageObject
{
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
     * Count of how many objects exist in the container.
     * 
     * @var int 
     */
    public $count = 0;
    
    /**
     * The total bytes used in the container.
     * 
     * @var int 
     */
    public $bytes = 0;
    
    /**
     * The service object.
     * 
     * @var AbstractService 
     */
    private $service;
    
    /**
     * URL of the container.
     * 
     * @var string 
     */
    private $containerUrl;

    /**
     * Creates the container object
     *
     * Creates a new container object or, if the $cdata object is a string,
     * retrieves the named container from the object store. If $cdata is an
     * array or an object, then its values are used to set this object.
     *
     * @param OpenCloud\ObjectStore $service - the ObjectStore service
     * @param mixed $cdata - if supplied, the name of the object
     */
    public function __construct(AbstractService $service, $cdata = null)
    {
        $this->getLogger()->info('Initializing CDN Container Service...');

        parent::__construct();

        $this->service = $service;

        // Populate data if set
        $this->populate($cdata);
    }
    
    /**
     * Returns the Service associated with the Container
     */
    public function getService()
    {
        return $this->service;
    }
    
    public function getClient()
    {
        return $this->getService()->getClient();
    }
    
    /**
     * Allow other objects to know what the primary key is.
     * 
     * @return string
     */
    public function primaryKeyField()
    {
        return 'name';
    }
    
    /**
     * Returns the URL of the container
     *
     * @return string
	 * @param string $subresource not used; required for compatibility
     * @throws NoNameError
     */
    public function url($subresource = '')
    {
        if (strlen($this->name) == 0) {
            throw new Exceptions\NoNameError(
            	Lang::translate('Container does not have an identifier')
            );
        }
        
        return Lang::noslash($this->getService()->url(rawurlencode($this->name)));
    }

    /**
     * Creates a new container with the specified attributes
     *
     * @param array $params array of parameters
     * @return boolean TRUE on success; FALSE on failure
     * @throws ContainerCreateError
     */
    public function create($params = array())
    {
        // Populate object and check container name
        $this->populate($params);
        $this->isValidName($this->name);
        
        // Dispatch
        $this->containerUrl = $this->url();
        $this->getClient()->put($this->url(), $this->metadataHeaders())->send();

        return true;
    }

    /**
     * Updates the metadata for a container
     *
     * @return boolean TRUE on success; FALSE on failure
     * @throws ContainerCreateError
     */
    public function update()
    {
        $this->getClient()->post($this->url(), 'POST', $this->metadataHeaders())
            ->setExpectedResponse(204)
            ->send();
        
        return true;
    }

    /**
     * Deletes the specified container
     *
     * @return boolean TRUE on success; FALSE on failure
     * @throws ContainerDeleteError
     */
    public function delete()
    {
        $this->getClient()->delete($this->url())
            ->setExpectedResponse(204)
            ->setExceptionHandler(array(
                404 => 'Container not found',
                409 => 'Container must be empty before deleting',
                300 => 'Unknown error'
            ))
            ->send();

        return true;
    }

    /**
     * Loads the object from the service
     *
     * @return void
     */
    public function refresh($name = null, $url = null)
    {
        $url = $this->url($name);
        $response = $this->getClient()->head($url, array('Accept' => '*/*'))
            ->setExceptionHandler(array(
                404 => 'Container not found'
            ))
            ->send();

        $this->count = $response->getHeader('X-Container-Object-Count');
        $this->bytes = $response->getHeader('X-Container-Bytes-Used');
        
        // parse the returned object
        $this->getMetadata($response);
    }

    /**
     * Validates that the container name is acceptable
     *
     * @param string $name the container name to validate
     * @return boolean TRUE if ok; throws an exception if not
     * @throws ContainerNameError
     */
    public function isValidName($name)
    {
        if (strlen($name) == 0) {
            throw new Exceptions\ContainerNameError(
            	'Container name cannot be blank'
            );
        }

        if (strpos($name, '/') !== false) {
            throw new Exceptions\ContainerNameError(
            	'Container name cannot contain "/"'
            );
        }

        if (strlen($name) > AbstractObjectService::MAX_CONTAINER_NAME_LEN) {
            throw new Exceptions\ContainerNameError(
            	'Container name is too long'
            );
        }

        return true;
    }

}
