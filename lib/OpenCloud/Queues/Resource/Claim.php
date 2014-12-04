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

namespace OpenCloud\Queues\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * A worker claims or checks out a message to perform a task. Doing so prevents
 * other workers from attempting to process the same messages.
 */
class Claim extends PersistentObject
{
    const LIMIT_DEFAULT = 10;
    const GRACE_DEFAULT = 43200;
    const TTL_DEFAULT = 43200;

    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $age;

    /**
     * @var array An array of messages.
     */
    private $messages;

    /**
     * How long the server should wait before releasing the claim in seconds.
     * The ttl value must be between 60 and 43200 seconds (12 hours is the
     * default but is configurable).
     *
     * @var int
     */
    private $ttl;

    /**
     * The message grace period in seconds. The value of grace must be between
     * 60 and 43200 seconds (12 hours the default, but configurable). The server
     * extends the lifetime of claimed messages to be at least as long as the
     * lifetime of the claim itself, plus a specified grace period to deal with
     * crashed workers (up to 1209600 or 14 days including claim lifetime). If a
     * claimed message would normally live longer than the grace period, it's
     * expiration will not be adjusted.
     *
     * @var int
     */
    private $grace;

    /**
     * Link.
     *
     * @var string
     */
    private $href;

    protected static $url_resource = 'claims';
    protected static $json_name = '';

    /**
     * Set the Href attribute and extrapolate the ID.
     *
     * @param $href
     * @return $this
     */
    public function setHref($href)
    {
        $paths = explode('/', $href);
        $this->id = end($paths);
        $this->href = $href;

        return $this;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function create($params = array())
    {
        return $this->noCreate();
    }

    /**
     * Updates the current Claim. It is recommended that you periodically renew
     * claims during long-running batches of work to avoid losing a claim in
     * the middle of processing a message. This is done by setting a new TTL for
     * the claim (which may be different from the original TTL). The server will
     * then reset the age of the claim and apply the new TTL.
     * {@inheritDoc}
     */
    public function update($params = array())
    {
        $grace = (isset($params['grace'])) ? $params['grace'] : $this->getGrace();
        $ttl = (isset($params['ttl'])) ? $params['ttl'] : $this->getGrace();

        $object = (object) array(
            'grace' => (int) $grace,
            'ttl'   => (int) $ttl
        );

        $json = json_encode($object);
        $this->checkJsonError();

        return $this->getClient()->patch($this->url(), self::getJsonHeader(), $json)->send();
    }
}
