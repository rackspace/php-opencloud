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

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Lang;

/**
 * PTR records are used for reverse DNS
 *
 * The PtrRecord object is nearly identical with the Record object. However,
 * the PtrRecord is a child of the service, and not a child of a Domain.
 */
class PtrRecord extends Record
{
    /** @var  */
    public $parent;

    protected static $json_name = false;
    protected static $json_collection_name = 'records';
    protected static $url_resource = 'rdns';

    private $link_rel;
    private $link_href;

    public function __construct($parent, $info = null)
    {
        parent::__construct($parent, $info);

        $this->type = 'PTR';
    }

    /**
     * DNS PTR Create() method requires a server
     *
     * Generally called as `Create(array('server'=>$server))`
     */
    public function create($params = array())
    {
        $this->populate($params);

        $this->link_rel = $this->parent->getService()->getName();
        $this->link_href = (string) $this->parent->getUrl();

        return parent::create();
    }

    /**
     * DNS PTR Update() method requires a server
     */
    public function update($params = array())
    {
        $this->populate($params);

        $this->link_rel = $this->parent->getService()->getName();
        $this->link_href = (string) $this->parent->getUrl();

        return parent::update();
    }

    /**
     * DNS PTR Delete() method requires a server
     *
     * Note that delete will remove ALL PTR records associated with the device
     * unless you pass in the parameter ip={ip address}
     *
     */
    public function delete()
    {
        $this->link_rel = $this->parent->getService()->Name();
        $this->link_href = (string) $this->parent->getUrl();

        $params = array('href' => $this->link_href);
        if (!empty($this->data)) {
            $params['ip'] = $this->data;
        }

        $url = clone $this->getUrl();
        $url->addPath('rdns')
            ->addPath($this->link_rel)
            ->setQuery($params);

        $response = $this->getClient()->delete($url)->send();

        return new AsyncResponse($this->getService(), Formatter::decode($response));
    }

    /**
     * Specialized JSON for DNS PTR creates and updates
     */
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

    /**
     * The Update() JSON requires a record ID
     */
    protected function updateJson($params = array())
    {
        $this->populate($params);
        $object = $this->createJson();
        $object->recordsList->records[0]->id = $this->id;

        return $object;
    }
}
