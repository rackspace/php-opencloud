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

namespace OpenCloud\DNS\Resource;

use OpenCloud\Common\Http\Message\Formatter;

/**
 * The Domain class represents a single domain
 *
 * Note that the `Subdomain` class is defined in this same file because of
 * mutual dependencies.
 */
class Domain extends AbstractResource
{
    public $id;
    public $accountId;
    public $ttl;
    public $updated;
    public $emailAddress;
    public $created;
    public $name;
    public $comment;

    protected static $json_name = false;
    protected static $json_collection_name = 'domains';
    protected static $url_resource = 'domains';

    protected $createKeys = array(
        'name',
        'emailAddress',
        'ttl',
        'comment'
    );

    protected $updateKeys = array(
        'emailAddress',
        'ttl',
        'comment'
    );

    private $records = array();
    private $subdomains = array();

    /**
     * returns a Record object
     *
     * Note that this method is available at the DNS level, but only for
     * PTR records.
     *
     * @return Record
     */
    public function record($info = null)
    {
        $resource = new Record($this->getService());
        $resource->setParent($this)->populate($info);

        return $resource;
    }

    /**
     * returns a Collection of Record objects
     *
     * @param array $filter query-string parameters
     * @return OpenCloud\DNS\Collection\DnsIterator
     */
    public function recordList($filter = array())
    {
        $url = $this->getUrl(Record::resourceName(), $filter);

        return $this->getParent()->collection(
            'OpenCloud\DNS\Resource\Record', $url, $this
        );
    }

    /**
     * returns a Subdomain object (child of current domain)
     *
     */
    public function subdomain($info = array())
    {
        $resource = new Subdomain($this->getService());
        $resource->setParent($this)->populate($info);

        return $resource;
    }

    /**
     * returns a Collection of subdomains
     *
     * The subdomains are all `DNS:Domain` objects that are children of
     * the current domain.
     *
     * @param array $filter key/value pairs for query string parameters
     *                      return \OpenCloud\Collection
     * @return OpenCloud\DNS\Collection\DnsIterator
     */
    public function subdomainList($filter = array())
    {
        $url = $this->getUrl(Subdomain::resourceName(), $filter);

        return $this->getParent()->collection(
            'OpenCloud\DNS\Resource\Subdomain', $url, $this
        );
    }

    /**
     * Adds a new record to the list (for multiple record creation)
     *
     * @api
     * @param Record $rec the record to add
     * @return integer the number of records
     */
    public function addRecord(Record $record)
    {
        $this->records[] = $record;

        return count($this->records);
    }

    /**
     * adds a new subdomain (for multiple subdomain creation)
     *
     * @api
     * @param Subdomain $subd the subdomain to add
     * @return integer the number of subdomains
     */
    public function addSubdomain(Subdomain $subdomain)
    {
        $this->subdomains[] = $subdomain;

        return count($this->subdomains);
    }

    /**
     * returns changes since a specified date/time
     *
     * @param string $since the date or time
     * @return DNS\Changes
     */
    public function changes($since = null)
    {
        $url = clone $this->getUrl();
        $url->addPath('changes');

        if ($since) {
            $url->setQuery(array('since' => $since));
        }

        $response = $this->getService()
            ->getClient()
            ->get($url)
            ->send();

        return Formatter::decode($response);
    }

    /**
     * exports the domain
     *
     * @return AsyncResponse
     */
    public function export()
    {
        return $this->getService()->asyncRequest($this->getUrl('export'));
    }

    /**
     * clones the domain to the specified target domain
     *
     * @param string  $newdomain the new domain to create from this domain
     * @param boolean $sub       to clone subdomains as well
     * @param boolean $comments  Replace occurrences of the reference domain
     *                           name with the new domain name in comments
     * @param boolean $email     Replace occurrences of the reference domain
     *                           name with the new domain name in email addresses on the cloned
     *                           (new) domain.
     * @param boolean $records   Replace occurrences of the reference domain
     *                           name with the new domain name in data fields (of records) on the
     *                           cloned (new) domain. Does not affect NS records.
     * @return AsyncResponse
     */
    public function cloneDomain(
        $newDomainName,
        $subdomains = true,
        $comments = true,
        $email = true,
        $records = true
    ) {
        $url = $this->getUrl();
        $url->addPath('clone');
        $url->setQuery(array(
            'cloneName'          => $newDomainName,
            'cloneSubdomains'    => $subdomains,
            'modifyComment'      => $comments,
            'modifyEmailAddress' => $email,
            'modifyRecordData'   => $records
        ));

        return $this->getService()->asyncRequest($url, 'POST');
    }

    /**
     * handles creation of multiple records at Create()
     *
     * @return \stdClass
     */
    protected function createJson()
    {
        $object = parent::createJson();

        // add records, if any
        if (count($this->records)) {
            $recordsObject = (object) array('records' => array());

            foreach ($this->records as $record) {
                $recordObject = new \stdClass;
                foreach ($record->getCreateKeys() as $key) {
                    if (isset($record->$key)) {
                        $recordObject->$key = $record->$key;
                    }
                }
                $recordsObject->records[] = $recordObject;
            }
            $object->domains[0]->recordsList = $recordsObject;
        }

        // add subdomains, if any
        if (count($this->subdomains)) {
            $subdomainsObject = (object) array('domains' => array());

            foreach ($this->subdomains as $subdomain) {
                $subdomainObject = new \stdClass;
                foreach ($subdomain->getCreateKeys() as $key) {
                    if (isset($subdomain->$key)) {
                        $subdomainObject->$key = $subdomain->$key;
                    }
                }
                $subdomainsObject->domains[] = $subdomainObject;
            }
            $object->domains[0]->subdomains = $subdomainsObject;
        }

        return $object;
    }
}
