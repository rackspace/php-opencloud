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

namespace OpenCloud\DNS;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\OpenStack;
use OpenCloud\Compute\Resource\Server;

/**
 * DNS Service.
 */
class Service extends AbstractService
{
    const DEFAULT_NAME = 'cloudDNS';
    const DEFAULT_REGION = '{ignore}';
    
    /**
     * creates a new DNS object
     *
     * @param \OpenCloud\OpenStack $conn connection object
     * @param string $serviceName the name of the service
     * @param string $serviceRegion (not currently used; DNS is regionless)
     * @param string $urltype the type of URL
     */
    public function __construct(
        OpenStack $connection,
        $serviceName,
        $serviceRegion,
        $urltype
    ) {
        
        $this->getLogger()->info('Initializing DNS...');
        
        parent::__construct(
            $connection,
            'rax:dns',
            $serviceName,
            $serviceRegion,
            $urltype
        );
    }

    /**
     * returns a DNS::Domain object
     *
     * @api
     * @param mixed $info either the ID, an object, or array of parameters
     * @return DNS\Domain
     */
    public function domain($info = null)
    {
        return new Resource\Domain($this, $info);
    }

    /**
     * returns a Collection of DNS::Domain objects
     *
     * @api
     * @param array $filter key/value pairs to use as query strings
     * @return \OpenCloud\Collection
     */
    public function domainList($filter = array())
    {
        $url = $this->url(Resource\Domain::ResourceName(), $filter);
        return $this->collection('OpenCloud\DNS\Resource\Domain', $url);
    }

    /**
     * returns a PtrRecord object for a server
     *
     * @param mixed $info ID, array, or object containing record data
     * @return Record
     */
    public function ptrRecord($info = null)
    {
        return new Resource\PtrRecord($this, $info);
    }

    /**
     * returns a Collection of PTR records for a given Server
     *
     * @param \OpenCloud\Compute\Server $server the server for which to
     *      retrieve the PTR records
     * @return Collection
     */
    public function ptrRecordList(Server $server)
    {
        $url = $this->url('rdns/' . $server->getService()->name(), array(
            'href' => $server->url()
        ));
        return $this->collection('OpenCloud\DNS\Resource\PtrRecord', $url);
    }

    /**
     * retrieves an asynchronous response
     *
     * This method calls the provided `$url` and expects an asynchronous
     * response. It checks for various HTTP error codes and returns
     * an `AsyncResponse` object. This object can then be used to poll
     * for the status or to retrieve the final data as needed.
     *
     * @param string $url the URL of the request
     * @param string $method the HTTP method to use
     * @param array $headers key/value pairs for headers to include
     * @param string $body the body of the request (for PUT and POST)
     * @return DNS\AsyncResponse
     */
    public function asyncRequest($url, $method = 'GET', $headers = array(), $body = null)
    {
        // perform the initial request
        $response = $this->getClient()->createRequest($method, $url, $headers, $body)->send();

        // debug
        $this->getLogger()->info('AsyncResponse [{body}]', array(
            'body' => $response->getDecodedBody()
        ));

        // return an AsyncResponse object
        return new Resource\AsyncResponse($this, $response->getDecodedBody());
    }

    /**
     * imports domain records
     *
     * Note that this function is called from the service (DNS) level, and
     * not (as you might suspect) from the Domain object. Because the function
     * return an AsyncResponse, the domain object will not actually exist
     * until some point after the import has occurred.
     *
     * @api
     * @param string $data the BIND_9 formatted data to import
     * @return DNS\AsyncResponse
     */
    public function import($data)
    {
        // determine the URL
        $url = $this->url('domains/import');

        $object = (object) array(
            'domains' => array(
                (object) array(
                    'contents'    => $data,
                    'contentType' => 'BIND_9'
                )
            )
        );

        // encode it
        $json = json_encode($object);

        // debug it
        $this->getLogger()->info('Importing [{json}]', array('json' => $json));

        // perform the request
        return $this->asyncRequest($url, 'POST', array(), $json);
    }

    /**
     * returns a list of limits
     */
    public function limits($type = null)
    {
        $url = $this->url('limits') . ($type ? "/$type" : '');
        
        $object = $this->getClient()
            ->get($url)
            ->send()
            ->getDecodedBody();
        
        return ($type) ? $object : $object->limits;
    }

    /**
     * returns an array of limit types
     *
     * @return array
     */
    public function limitTypes()
    {
        $response = $this->getClient()->get($this->url('limits/types'))->send();
        $object = $response->getDecodedBody();
        return $object->limitTypes;
    }

}
