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
use OpenCloud\Common\Http\Message\Formatter;

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
     * @param $client HTTP client
     * @return Endpoint
     */
    public static function factory($object, $client)
    {
        $endpoint = new self();

        if (isset($object->publicURL)) {
            $endpoint->setPublicUrl($this->getVersionedUrl($object->publicURL, $client));
        }
        if (isset($object->internalURL)) {
            $endpoint->setPrivateUrl($this->getVersionedUrl($object->internalURL, $client));
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

    /**
     * @param string $url URL of endpoint
     * @param object $client HTTP client
     * @return string URL of endpoint, with version
     */
    protected function getVersionedUrl($url, $client)
    {
      try {
          // Make GET request to URL
          $response = Formatter::decode($client->get($url)->send());

          // Attempt to parse response and determine URL for given $version
          if (!property_exists($response, 'versions')) {
              return $url;
          }

          foreach ($response->versions as $version) {
              if ($version->status == 'CURRENT') {
                  foreach ($version->links as $link) {
                      if ($link->rel == 'self') {
                          return $link->href;
                      }
                  }
              }
          }

          // If we've reached this point, we could not find a versioned
          // URL in the response; return the original URL as-is
          return $url;

      } catch (Exception $e) {
          return $url;
      }
    }
}
