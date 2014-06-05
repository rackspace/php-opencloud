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
 * PTR records are used for reverse DNS
 *
 * The PtrRecord object is nearly identical with the Record object. However,
 * the PtrRecord is a child of the service, and not a child of a Domain.
 */
class PtrRecord extends Record
{
    /** @var HasPtrRecordsInterface The device which this record refers to */
    public $server;

    protected static $json_name = false;
    protected static $json_collection_name = 'records';
    protected static $url_resource = 'rdns';

    private $link_rel;
    private $link_href;

    public function __construct($service, $info = null)
    {
        parent::__construct($service, $info);

        $this->type = 'PTR';
    }

    /**
     * Used to internally populate this object with the appropriate type checks
     *
     * @param array $params
     * @throws \InvalidArgumentException If no parent device set
     */
    protected function populateRecord(array $params = array())
    {
        if (!isset($params['parent'])) {
            throw new \InvalidArgumentException('You must set a `parent` device');
        }

        $this->setDeviceParent($params['parent']);
        unset($params['parent']);

        parent::populate($params);
    }

    /**
     * Set the parent device
     *
     * @param HasPtrRecordsInterface $parent
     */
    public function setDeviceParent(HasPtrRecordsInterface $parent)
    {
        $this->server = $parent;
    }

    /**
     * @return HasPtrRecordsInterface
     */
    public function getDeviceParent()
    {
        return $this->server;
    }

    public function create($params = array())
    {
        $this->populateRecord($params);

        $this->link_rel = $this->getDeviceParent()->getService()->getName();
        $this->link_href = (string) $this->getDeviceParent()->getUrl();

        return parent::create();
    }

    public function update($params = array())
    {
        $this->populateRecord($params);

        $this->link_rel = $this->getDeviceParent()->getService()->getName();
        $this->link_href = (string) $this->getDeviceParent()->getUrl();

        return parent::update();
    }

    public function delete()
    {
        $this->link_rel = $this->getDeviceParent()->getService()->Name();
        $this->link_href = (string) $this->getDeviceParent()->getUrl();

        $params = array('href' => $this->link_href);
        if (!empty($this->data)) {
            $params['ip'] = $this->data;
        }

        $url = clone $this->getUrl();
        $url->addPath('..')
            ->normalizePath()
            ->addPath($this->link_rel)
            ->setQuery($params);

        $response = $this->getClient()->delete($url)->send();

        return new AsyncResponse($this->getService(), Formatter::decode($response));
    }

    protected function createJson()
    {
        return (object) array(
            'recordsList' => parent::createJson(),
            'link' => array(
                'href' => $this->link_href,
                'rel'  => $this->link_rel
            )
        );
    }

    protected function updateJson($params = array())
    {
        $this->populate($params);

        $object = $this->createJson();
        $object->recordsList->records[0]->id = $this->id;

        return $object;
    }
}
