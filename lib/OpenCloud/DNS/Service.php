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
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\OpenStack;
use OpenCloud\Compute\Server;

/**
 * DNS Service.
 */
class Service extends AbstractService
{

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
        return new Domain($this, $info);
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
        $url = $this->url(Domain::ResourceName(), $filter);
        return $this->collection('OpenCloud\DNS\Domain', $url);
    }

    /**
     * returns a PtrRecord object for a server
     *
     * @param mixed $info ID, array, or object containing record data
     * @return Record
     */
    public function ptrRecord($info = null)
    {
        return new PtrRecord($this, $info);
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
        return $this->collection('\OpenCloud\DNS\PtrRecord', $url);
    }

    /**
     * performs a HTTP request
     *
     * This method overrides the request with JSON content type
     *
     * @param string $url the URL to target
     * @param string $method the HTTP method to use
     * @param array $headers key/value pairs for headers to include
     * @param string $body the body of the request (for PUT and POST)
     * @return \OpenCloud\HttpResponse
     */
    public function request(
    	$url,
    	$method = 'GET',
    	array $headers = array(),
    	$body = null
    ) {
        $headers['Accept'] = RAXSDK_CONTENT_TYPE_JSON;
        $headers['Content-Type'] = RAXSDK_CONTENT_TYPE_JSON;
        return parent::request($url, $method, $headers, $body);
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
        $resp = $this->request($url, $method, $headers, $body);

        // @codeCoverageIgnoreStart
        if ($resp->HttpStatus() > 204) {
            throw new Exceptions\AsyncHttpError(sprintf(
                Lang::translate('Unexpected HTTP status for async request: URL [%s] method [%s] status [%s] response [%s]'),
                $url,
                $method,
                $resp->HttpStatus(),
                $resp->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

        // debug
        $this->getLogger()->info('AsyncResponse [{body}]', array(
            'body' => $resp->httpBody()
        ));

        // return an AsyncResponse object
        return new AsyncResponse($this, $resp->httpBody());
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
     *
     */
    public function limits($type = null)
    {
        $url = $this->url('limits') . ($type ? "/$type" : '');
        $object = $this->simpleRequest($url);
        return ($type) ? $object : $object->limits;
    }

    /**
     * returns an array of limit types
     *
     * @return array
     */
    public function limitTypes()
    {
        $object = $this->simpleRequest($this->url('limits/types'));
        return $object->limitTypes;
    }

    /**
     * Performs a simple request and returns the JSON as an object
     *
     * @param string $url the URL to GET
     */
    public function simpleRequest($url)
    {
        // Perform the request
        $response = $this->request($url);

        // Check for errors
        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 202) {
            throw new Exceptions\HttpError(sprintf(
                Lang::translate('Unexpected status [%s] for URL [%s], body [%s]'),
                $response->HttpStatus(),
                $url,
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

        // Decode the JSON
        $json = $response->httpBody();
        $this->getLogger()->info('Limit Types JSON [{json}]', array('json' => $json));

        $object = json_decode($json);

        $this->checkJsonError();

        return $object;
    }

}
