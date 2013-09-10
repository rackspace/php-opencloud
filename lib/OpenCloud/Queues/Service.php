<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.1
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Queues;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\Common\Exceptions\InvalidArgumentError;

/**
 * Cloud Queues is an open source, scalable, and highly available message and 
 * notifications service. Users of this service can create and manage a 
 * "producer-consumer" or a "publisher-subscriber" model from one simple API. It 
 * is made up of a few basic components: queues, messages, claims, and stats.
 * 
 * In the producer-consumer model, users create queues where producers 
 * can post messages. Workers, or consumers, may then claim those messages and 
 * delete them once complete. A single claim may contain multiple messages, and 
 * administrators are given the ability to query claims for status.
 * 
 * In the publisher-subscriber model, messages are posted to a queue like above, 
 * but messages are never claimed. Instead, subscribers, or watchers, simply 
 * send GET requests to pull all messages since their last request. In this 
 * model, a message will remain in the queue, unclaimed, until the message's 
 * time to live (TTL) has expired.
 * 
 * Here is an overview of the Cloud Queues workflow:
 * 
 * 1. You create a queue to which producers post messages.
 * 
 * 2. Producers post messages to the queue.
 * 
 * 3. Workers claim messages from the queue, complete the work in that message, 
 *      and delete the message.
 * 
 * 4. If a worker plans to be offline before its message completes, the worker 
 *      can retire the claim TTL, putting the message back into the queue for 
 *      another worker to claim.
 * 
 * 5. Subscribers monitor the claims of these queues to keep track of activity 
 *      and help troubleshoot if things go wrong.
 *
 * For the majority of use cases, Cloud Queues itself will not be responsible 
 * for the ordering of messages. If there is only a single producer, however, 
 * Cloud Queueing guarantees that messages are handled in a First In, First Out 
 * (FIFO) order.
 */
class Service extends AbstractService
{
    /**
     * Main service constructor.
     * 
     * @param OpenStack $connection
     * @param mixed $serviceName
     * @param mixed $serviceRegion
     * @param mixed $urlType
     * @return void
     */
    public function __construct(OpenStack $connection, $serviceName, $serviceRegion, $urlType)
    {
        parent::__construct(
            $connection, 'rax:monitor', $serviceName, $serviceRegion, $urlType
        );
    }
    
    /**
     * This operation lists queues for the project, sorting the queues 
     * alphabetically by name.
     * 
     * @param array $params An associative array of optional parameters:
     * 
     * - marker (string) Specifies the name of the last queue received in a 
     *                   previous request, or none to get the first page of 
     *                   results. Optional.
     * 
     * - limit (integer) Specifies up to 20 (the default, but configurable) 
     *                   queues to return. Optional.
     * 
     * - detailed (bool) Determines whether queue metadata is included in the 
     *                   response. Optional.
     * 
     * @return Collection
     */
    public function listQueues(array $params = array())
    {
        return $this->resourceList('Queue', $this->url('queues', $params));
    }
    
    /**
     * Return an empty Queue object.
     * 
     * @return Queue
     */
    public function getQueue()
    {
        return $this->resource('Queue');
    }
    
    /**
     * This operation checks to see if the specified queue exists.
     * 
     * @param  string $name The queue name that you want to check
     * @return bool
     */
    public function hasQueue($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentError(sprintf(
                'You must provide a queue name (a string). You provided: %s',
                print_r($name, true)
            ));
        }
        
        $response = $this->request($this->url("queues/$name"), 'HEAD');
        
        return $response->httpStatus() == 204;
    }
    
}