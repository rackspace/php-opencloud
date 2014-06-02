<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Compute;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Http\Client;
use OpenCloud\Common\Service\NovaService;

/**
 * The Compute class represents the OpenStack Nova service.
 *
 * It is constructed from a OpenStack object and requires a service name,
 * region, and URL type to select the proper endpoint from the service
 * catalog. However, constants can be used to define default values for
 * these to make it easier to use:
 *
 * Creating a compute object:
 *
 * <code>
 * $rackspace = new OpenCloud\Rackspace(...);
 * $dallas = new Compute(
 *    $rackspace,              // connection
 *   'cloudServersOpenStack',  // the service's name
 *   'DFW',                    // region identifier
 *   'publicURL'               // URL type
 *  );
 * </code>
 *
 * The easy way (with defaults); this assumes that the constants (RAXSDK_...)
 * are defined elsewhere *before* the inclusion of the first SDK library file:
 *
 * <code>
 * $rackspace = new OpenCloud\Rackspace(...);
 * $dallas = new OpenCloud\Compute($rackspace); // uses defaults
 * </code>
 *
 */
class Service extends NovaService
{
    const DEFAULT_TYPE = 'compute';
    const DEFAULT_NAME = 'cloudServersOpenStack';

    protected $additionalExtensions = array('OS-FLV-DISABLED');

    public function __construct(Client $client, $type = null, $name = null, $region = null, $urlType = null)
    {
        parent::__construct($client, $type, $name, $region, $urlType);

        // @see https://github.com/rackspace/php-opencloud/issues/353
        if (strpos($this->getUrl()->getPath(), '/v1.0/') !== false) {
            throw new Exceptions\UnsupportedVersionError(sprintf(
                'Sorry; API version /v1.0 is not supported [%s]',
                $this->getUrl()
            ));
        }

        $this->loadNamespaces();
    }

    /**
     * Returns a Server object associated with this Compute service
     *
     * This is a factory method and should generally be used to create server
     * objects (thus ensuring that they are correctly associated with the
     * server) instead of calling the Server class explicitly.
     *
     * @api
     * @param string $id - if specified, the server with the ID is retrieved
     * @returns Resource\Server object
     */
    public function server($id = null)
    {
        return new Resource\Server($this, $id);
    }

    /**
     * Returns a Collection of server objects, filtered by the specified
     * parameters
     *
     * This is a factory method and should normally be called instead of
     * creating a ServerList object directly.
     *
     * @api
     * @param boolean $details - if TRUE, full server details are returned; if
     *                         FALSE, just the minimal set of info is listed. Defaults to TRUE;
     *                         you might set this to FALSE to improve performance at the risk of
     *                         not having all the information you need.
     * @param array   $filter  - a set of key/value pairs that is passed to the
     *                         servers list for filtering
     * @returns \OpenCloud\Common\Collection
     */
    public function serverList($details = true, array $filter = array())
    {
        $url = $this->getUrl(Resource\Server::resourceName() . (($details) ? '/detail' : ''), $filter);

        return $this->collection('OpenCloud\Compute\Resource\Server', $url);
    }

    /**
     * Returns a Network object
     *
     * @api
     * @param string $id the network ID
     * @return Resource\Network
     */
    public function network($id = null)
    {
        return new Resource\Network($this, $id);
    }

    /**
     * Returns a Collection of Network objects
     *
     * @api
     * @param array $filter array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function networkList($filter = array())
    {
        return $this->collection('OpenCloud\Compute\Resource\Network');
    }

    /**
     * Returns an image from the service
     *
     * This is a factory method and should normally be called instead of
     * creating an Image object directly.
     *
     * @api
     * @param string $id - if supplied, returns the image with the specified ID.
     * @return Resource\Image object
     */
    public function image($id = null)
    {
        return new Resource\Image($this, $id);
    }

    /**
     * Returns a Collection of images (class Image)
     *
     * This is a factory method and should normally be used instead of creating
     * an ImageList object directly.
     *
     * @api
     * @param boolean $details - if TRUE (the default), returns complete image
     *                         details. Set to FALSE to improve performance, but only return a
     *                         minimal set of data
     * @param array   $filter  - key/value pairs to pass to the images resource.
     *                         The actual values available here are determined by the OpenStack
     *                         code and any extensions installed by your cloud provider;
     *                         see http://docs.rackspace.com/servers/api/v2/cs-devguide/content/List_Images-d1e4435.html
     *                         for current filters available.
     * @return \OpenCloud\Common\Collection
     */
    public function imageList($details = true, array $filter = array())
    {
        $url = clone $this->getUrl();
        $url->addPath('images');

        if ($details === true) {
            $url->addPath('detail');
        }

        if (count($filter)) {
            $url->setQuery($filter);
        }

        return $this->resourceList('Image', $url);
    }

    public function keypair($data = null)
    {
        return $this->resource('KeyPair', $data);
    }

    public function listKeypairs()
    {
        return $this->resourceList('KeyPair', null, $this);
    }
}
