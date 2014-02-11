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

namespace OpenCloud\Common\Service;

use Guzzle\Http\Url;

/**
 * An endpoint serves as a location which receives and emits API interactions. It will therefore also host
 * particular API resources. Each endpoint object has different access methods - one receives network connections over
 * the public Internet, another receives traffic through an internal network. You will be able to access the latter
 * from a Server, for example, in the same Region - which will incur no bandwidth charges, and be quicker.
 */
class Endpoint
{
    /**
     * @var \Guzzle\Http\Url
     */
    private $publicUrl;

    /**
     * @var \Guzzle\Http\Url
     */
    private $privateUrl;

    /**
     * @var string
     */
    private $region;

    /**
     * @param $object
     * @return Endpoint
     */
    public static function factory($object)
    {
        $endpoint = new self();

        if (isset($object->publicURL)) {
            $endpoint->setPublicUrl($object->publicURL);
        }
        if (isset($object->internalURL)) {
            $endpoint->setPrivateUrl($object->internalURL);
        }
        if (isset($object->region)) {
            $endpoint->setRegion($object->region);
        }

        return $endpoint;
    }

    /**
     * @param $publicUrl
     * @return $this
     */
    public function setPublicUrl($publicUrl)
    {
        $this->publicUrl = Url::factory($publicUrl);

        return $this;
    }

    /**
     * @return Url
     */
    public function getPublicUrl()
    {
        return $this->publicUrl;
    }

    /**
     * @param $privateUrl
     * @return $this
     */
    public function setPrivateUrl($privateUrl)
    {
        $this->privateUrl = Url::factory($privateUrl);

        return $this;
    }

    /**
     * @return Url
     */
    public function getPrivateUrl()
    {
        return $this->privateUrl;
    }

    /**
     * @param $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }
}
