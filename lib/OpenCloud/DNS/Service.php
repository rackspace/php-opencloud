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

namespace OpenCloud\DNS;

use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Common\Exceptions\DomainNotFoundException;
use OpenCloud\DNS\Collection\DnsIterator;
use OpenCloud\DNS\Resource\AsyncResponse;
use OpenCloud\DNS\Resource\Domain;
use OpenCloud\DNS\Resource\HasPtrRecordsInterface;

/**
 * DNS Service.
 */
class Service extends CatalogService
{
    const DEFAULT_TYPE = 'rax:dns';
    const DEFAULT_NAME = 'cloudDNS';

    protected $regionless = true;

    public function collection($class, $url = null, $parent = null, $data = null)
    {
        $options = $this->makeResourceIteratorOptions($this->resolveResourceClass($class));
        $options['baseUrl'] = $url;

        $parent = $parent ? : $this;

        return DnsIterator::factory($parent, $options, $data);
    }

    /**
     * Returns a domain
     *
     * @param mixed $info either the ID, an object, or array of parameters
     * @return Resource\Domain
     */
    public function domain($info = null)
    {
        return $this->resource('Domain', $info);
    }

    /**
     * Returns a domain, given a domain name
     *
     * @param string $domainName Domain name
     * @return Domain the domain object
     */
    public function domainByName($domainName)
    {
        $domainList = $this->domainList(array("name" => $domainName));

        if (count($domainList) != 1) {
            throw new DomainNotFoundException();
        }

        return $this->resource('Domain', $domainList[0]);
    }


    /**
     * Returns a collection of domains
     *
     * @param array $filter key/value pairs to use as query strings
     * @return OpenCloud\DNS\Collection\DnsIterator
     */
    public function domainList($filter = array())
    {
        $url = $this->getUrl(Domain::resourceName());
        $url->setQuery($filter);

        return $this->resourceList('Domain', $url);
    }

    /**
     * returns a PtrRecord object for a server
     *
     * @param mixed $info ID, array, or object containing record data
     * @return Resource\Record
     */
    public function ptrRecord($info = null)
    {
        return $this->resource('PtrRecord', $info);
    }

    /**
     * returns a Collection of PTR records for a given Server
     *
     * @param \OpenCloud\Compute\Resource\Server $server the server for which to
     *                                                   retrieve the PTR records
     * @return OpenCloud\DNS\Collection\DnsIterator
     */
    public function ptrRecordList(HasPtrRecordsInterface $parent)
    {
        $url = $this->getUrl()
            ->addPath('rdns')
            ->addPath($parent->getService()->getName())
            ->setQuery(array('href' => (string) $parent->getUrl()));

        return $this->resourceList('PtrRecord', $url);
    }

    /**
     * retrieves an asynchronous response
     *
     * This method calls the provided `$url` and expects an asynchronous
     * response. It checks for various HTTP error codes and returns
     * an `AsyncResponse` object. This object can then be used to poll
     * for the status or to retrieve the final data as needed.
     *
     * @param string $url     the URL of the request
     * @param string $method  the HTTP method to use
     * @param array  $headers key/value pairs for headers to include
     * @param string $body    the body of the request (for PUT and POST)
     * @return Resource\AsyncResponse
     */
    public function asyncRequest($url, $method = 'GET', $headers = array(), $body = null)
    {
        $response = $this->getClient()->createRequest($method, $url, $headers, $body)->send();

        return new AsyncResponse($this, Formatter::decode($response));
    }

    /**
     * Imports domain records
     *
     * Note that this function is called from the service (DNS) level, and
     * not (as you might suspect) from the Domain object. Because the function
     * return an AsyncResponse, the domain object will not actually exist
     * until some point after the import has occurred.
     *
     * @param string $data the BIND_9 formatted data to import
     * @return Resource\AsyncResponse
     */
    public function import($data)
    {
        $url = clone $this->getUrl();
        $url->addPath('domains');
        $url->addPath('import');

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

        // perform the request
        return $this->asyncRequest($url, 'POST', self::getJsonHeader(), $json);
    }

    /**
     * returns a list of limits
     */
    public function limits($type = null)
    {
        $url = $this->getUrl('limits');

        if ($type) {
            $url->addPath($type);
        }

        $response = $this->getClient()->get($url)->send();
        $body = Formatter::decode($response);

        return isset($body->limits) ? $body->limits : $body;
    }

    /**
     * returns an array of limit types
     *
     * @return array
     */
    public function limitTypes()
    {
        $response = $this->getClient()->get($this->getUrl('limits/types'))->send();
        $body = Formatter::decode($response);

        return $body->limitTypes;
    }

    /**
     * List asynchronous responses' statuses.
     * @see http://docs.rackspace.com/cdns/api/v1.0/cdns-devguide/content/viewing_status_all_asynch_jobs.html
     *
     * @param array $query Any query parameters. Optional.
     * @return OpenCloud\DNS\Collection\DnsIterator
     */
    public function listAsyncJobs(array $query = array())
    {
        $url = clone $this->getUrl();
        $url->addPath('status');
        $url->setQuery($query);

        return DnsIterator::factory($this, array(
            'baseUrl'        => $url,
            'resourceClass'  => 'AsyncResponse',
            'key.collection' => 'asyncResponses'
        ));
    }

    public function getAsyncJob($jobId, $showDetails = true)
    {
        $url = clone $this->getUrl();
        $url->addPath('status');
        $url->addPath((string) $jobId);
        $url->setQuery(array('showDetails' => ($showDetails) ? 'true' : 'false'));

        $response = $this->getClient()->get($url)->send();

        return new AsyncResponse($this, Formatter::decode($response));
    }
}
