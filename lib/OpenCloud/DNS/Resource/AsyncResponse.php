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

use Guzzle\Http\Url;
use OpenCloud\Common\Constants\State;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Service\ServiceInterface;

/**
 * The AsyncResponse class encapsulates the data returned by a Cloud DNS
 * asynchronous response.
 */
class AsyncResponse extends PersistentObject
{
    const DEFAULT_INTERVAL = 2;

    public $jobId;
    public $callbackUrl;
    public $status;
    public $requestUrl;
    public $verb;
    public $request;
    public $response;
    public $error;
    public $domains;

    protected static $json_name = false;

    /**
     * constructs a new AsyncResponse object from a JSON
     * string
     *
     * @param \OpenCloud\Service $service the calling service
     * @param string             $json    the json response from the initial request
     */
    public function __construct(ServiceInterface $service, $object = null)
    {
        if (!$object) {
            return;
        }

        parent::__construct($service, $object);
    }

    /**
     * URL for status
     *
     * We always show details
     *
     * @return string
     */
    public function getUrl($path = null, array $query = array())
    {
        return Url::factory($this->callbackUrl)
            ->setQuery(array('showDetails' => 'True'));
    }

    /**
     * returns the Name of the request (the job ID)
     *
     * @return string
     */
    public function name()
    {
        return $this->jobId;
    }

    /**
     * overrides for methods
     */
    public function create($params = array())
    {
        return $this->noCreate();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }

    public function delete()
    {
        return $this->noDelete();
    }

    public function primaryKeyField()
    {
        return 'jobId';
    }

    public function waitFor($state = null, $timeout = null, $callback = null, $interval = null)
    {
        $state    = $state ?: 'COMPLETED';
        $timeout  = $timeout ?: State::DEFAULT_TIMEOUT;
        $interval = $interval ?: self::DEFAULT_INTERVAL;

        $jobUrl = Url::factory($this->callbackUrl);
        $jobUrl->setQuery(array('showDetails' => 'true'));

        $continue = true;
        $startTime = time();
        $states = array('ERROR', $state);

        while ($continue) {
            $body = $this->getClient()->get($jobUrl)->send()->json();

            if ($callback) {
                call_user_func($callback, $body);
            }

            if (in_array($body['status'], $states) || (time() - $startTime) > $timeout) {
                $continue = false;
            }

            sleep($interval);
        }

        return isset($body['response']) ? $body['response'] : false;
    }
}
