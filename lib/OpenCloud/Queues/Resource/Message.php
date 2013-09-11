<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Queues\Resource;

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
    public $id;
    
    /**
     * The number of seconds since ts, relative to the server's clock.
     * 
     * @var int 
     */
    protected $age;
    
    /**
     * Defines how long a message will be accessible. The message expires after 
     * ($ttl - $age) seconds.
     * 
     * @var int 
     */
    protected $ttl;
    
    /**
     * The arbitrary document submitted along with the original request to post 
     * the message.
     * 
     * @var mixed 
     */
    protected $body;
    
    /**
     * An opaque relative URI that the client can use to uniquely identify a 
     * message resource, and interact with it.
     * 
     * @var string 
     */
    protected $href;
    
    protected static $url_resource = 'messages';
    protected static $json_collection_name = 'messages';
    protected static $json_name = '';
    
    /**
     * Get (read-only) ID.
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get (read-only) age.
     * 
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }
    
    /**
     * Get (read-only) href value.
     * 
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }
    
    /**
     * Set the TTL when creating a new message.
     * 
     * @param string $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }
    
    /**
     * Get TTL.
     * 
     * @return string
     */
    public function getTtl()
    {
        return $this->ttl;
    }
    
    /**
     * Set the body when creating a new message.
     * 
     * @param string $body
     */
    public function setBody($body)
    {
        if ($id = $this->getId()) {
            $this->getLogger()->warning(
                'Attempting to change a read-only value (Body) on a message '
                . 'which already exists (ID = {id})',
                array('id' => $id)
            );
        } else {
            $this->body = $body;
        }
        
        return $this;
    }
    
    /**
     * Get body.
     * 
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createJson()
    {
        return (object) array(
            'ttl'  => $this->getTtl(),
            'body' => $this->getBody()
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function update($params = array())
    {
        return $this->noUpdate();
    }
    
    /**
     * This operation immediately deletes the specified message.
     * 
     * @param  string $claimId  Specifies that the message should be deleted 
     *      only if it has the specified claim ID, and that claim has not expired. 
     *      This is useful for ensuring only one agent processes any given 
     *      message. Whenever a worker client's claim expires before it has a 
     *      chance to delete a message it has processed, the worker must roll 
     *      back any actions it took based on that message because another worker 
     *      will now be able to claim and process the same message.
     *      
     *      If you do *not* specify $claimId, but the message is claimed, the 
     *      operation fails. You can only delete claimed messages by providing 
     *      an appropriate $claimId.
     * 
     * @return boolean
     * @throws DeleteMessageException
     */
    public function delete($claimId = null)
    {
        $url = $this->url(null, array('claim_id' => $claimId));
        $response = $this->getService()->request($url, 'DELETE');
        
        if ($response->httpStatus() != 204) {
            throw new DeleteMessageException(sprintf(
                'Error claiming message with ID %s. HTTP status [%i] and '
                . 'HTTP body [%s]',
                $this->getId(),
                $response->httpStatus(),
                $response->httpBody()
            ));
        }
        
        return true;
    }
    
    public function name()
    {
        return $this->getId();
    }
    
}