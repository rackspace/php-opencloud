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

use Guzzle\Http\Url;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Queues\Exception\DeleteMessageException;

/**
 * A message is a task, a notification, or any meaningful data that gets posted
 * to the queue. A message exists until it is deleted by a recipient or
 * automatically by the system based on a TTL (time-to-live) value.
 */
class Message extends PersistentObject
{
    /**
     * @var string
     */
    private $id;

    /**
     * The number of seconds since ts, relative to the server's clock.
     *
     * @var int
     */
    private $age;

    /**
     * Defines how long a message will be accessible. The message expires after
     * ($ttl - $age) seconds.
     *
     * @var int
     */
    private $ttl = 600;

    /**
     * The arbitrary document submitted along with the original request to post
     * the message.
     *
     * @var mixed
     */
    private $body;

    /**
     * An opaque relative URI that the client can use to uniquely identify a
     * message resource, and interact with it.
     *
     * @var string
     */
    private $href;

    protected static $url_resource = 'messages';
    protected static $json_collection_name = 'messages';
    protected static $json_name = '';

    /**
     * Set href (and ID).
     *
     * @param  string $href
     * @return self
     */
    public function setHref($href)
    {
        // We have to extract the ID out of the Href. Nice...
        preg_match('#.+/([\w]+)#', $href, $match);
        if (!empty($match)) {
            $this->setId($match[1]);
        }

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

    public function createJson()
    {
        return (object) array(
            'ttl'  => $this->getTtl(),
            'body' => $this->getBody()
        );
    }

    public function create($params = array())
    {
        $this->getLogger()->alert('Please use Queue::createMessage() or Queue::createMessages()');

        return $this->noCreate();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }

    /**
     * This operation immediately deletes the specified message.
     *
     * @param  string $claimId Specifies that the message should be deleted
     *                         only if it has the specified claim ID, and that claim has not expired.
     *                         This is useful for ensuring only one agent processes any given
     *                         message. Whenever a worker client's claim expires before it has a
     *                         chance to delete a message it has processed, the worker must roll
     *                         back any actions it took based on that message because another worker
     *                         will now be able to claim and process the same message.
     *
     *      If you do *not* specify $claimId, but the message is claimed, the
     *      operation fails. You can only delete claimed messages by providing
     *      an appropriate $claimId.
     *
     * @return bool
     * @throws DeleteMessageException
     */
    public function delete($claimId = null)
    {
        $url = $this->url(null, array('claim_id' => $claimId));
        $this->getClient()
            ->delete($url)
            ->send();

        return true;
    }

    /**
     * If this message has been claimed, retrieve the claim id.
     *
     * @return string
     */
    public function getClaimIdFromHref()
    {
        $url = Url::factory($this->href);

        return $url->getQuery()->get('claim_id');
    }
}
