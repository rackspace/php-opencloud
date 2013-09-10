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
use OpenCloud\Common\Exceptions\InvalidArgumentError;
use OpenCloud\Queues\Exception;

/**
 * A queue holds messages. Ideally, a queue is created per work type. For example, 
 * if you want to compress files, you would create a queue dedicated to this job. 
 * Any application that reads from this queue would only compress files.
 */
class Queue extends PersistentObject
{
    /**
     * @var string 
     */
    public $id;
    
    /**
     * The name given to the queue. The name MUST NOT exceed 64 bytes in length, 
     * and is limited to US-ASCII letters, digits, underscores, and hyphens.
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Miscellaneous, user-defined information about the queue.
     * 
     * @var array|Metadata 
     */
    protected $metadata;
    
    protected static $url_resource = 'queues';
    protected static $json_collection_name = 'queues';
    
    public $createKeys = array('name');
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Sets the metadata for this Queue. 
     * 
     * @param array|object|Metadata $data  The data we want to use
     * @param bool $query If set to TRUE, we will make the $data persistent and 
     *      save it to the API.
     * @return Queue
     * @throws Exception\QueueMetadataException
     */
    public function setMetadata($data, $query = true)
    {
        // Check for either objects, arrays or Metadata objects
        if (is_array($data) || is_object($data)) {
            $metadata = new Metadata();
            $metadata->setArray($data);
        } elseif ($data instanceof Metadata) {
            $metadata = $data;
        } else {
            throw new InvalidArgumentError(sprint(
                'You must specify either an array/object of parameters, or an '
                . 'instance of Metadata. You provided: %s',
                print_r($data, true)
            ));
        }
        
        // Set property
        $this->metadata = $metadata;
        
        // Is this a persistent change?
        if ($query === true) {
            
            // Get metadata properties as JSON-encoded object
            $json = json_encode((object) get_object_vars($metadata));
            $url  = $this->url('metadata');
            $response = $this->getService()->request($url, 'POST', array(), $json);
            
            // Catch errors
            if ($response->httpStatus() != 204) {
                throw new Exception\QueueMetadataException(sprintf(
                    'Unable to set metadata for this Queue'
                ));
            }
            
        }
        
        return $this;
    }
    
    /**
     * Returns the metadata associated with a Queue.
     * 
     * @param bool $query If set to TRUE, we will query the API for the current
     *      metadata, rather than relying on the value set in this object. Once
     *      returned, the API version will override the object value.
     * @return Metadata|null
     * @throws Exceptions\QueueMetadataException
     */
    public function getMetadata($query = true)
    {
        if ($query === true) {
            
            $response = $this->getService()->request($this->url('metadata'));
            
            if ($response->httpStatus() != 200) {
                throw new Exception\QueueMetadataException(sprintf(
                    'Unable to gather metadata for this Queue from API'
                ));
            }
            
            $data = json_decode($response->httpBody());
            $this->checkJsonError();
            
            $metadata = new Metadata();
            $metadata->setArray($data);
            $this->setMetadata($metadata, true);
            
        }
        
        return $this->metadata;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createJson()
    {
        return (object) array(
            'queue_name' => $this->getName(),
            'metadata'   => $this->getMetadata(false)
        );
    }
    
    /**
     * Needed to set correct URL (for delete method among others).
     * 
     * @return string
     */
    public function primaryKeyField()
    {
        return 'name';
    }
    
    /**
     * {@inheritDoc}
     */
    public function update()
    {
        return $this->noUpdate();
    }
    
    /**
     * This operation returns queue statistics including how many messages are 
     * in the queue, broken out by status.
     * 
     * @return object
     */
    public function getStats()
    {
        $body = $this->customAction('stats');
        return $body->messages;
    }
    
    
    /*** MESSAGES ***/
    
    /**
     * Gets a message either by a specific ID, or, if no ID is specified, just 
     * an empty Message object.
     * 
     * @param string|null $id If a string, then the service will retrieve an 
     *      individual message based on its specific ID. If NULL, then an empty
     *      object is returned for further use.
     * @return Message
     */
    public function getMessage($id = null)
    {
        return $this->getService()->resource('Message', $id)->setParent($this);
    }
    
    /**
     * Lists messages according to certain filter options. Results are ordered 
     * by age, oldest message first. All of the parameters are optional.
     * 
     * @param array $options An associative array of filtering parameters:
     * 
     * - ids (array) A two-dimensional array of IDs to retrieve.
     * 
     * - claim_id (string) The claim ID to which the message is associated.
     * 
     * - marker (string) An opaque string that the client can use to request the 
     *      next batch of messages. If not specified, the API will return all 
     *      messages at the head of the queue (up to limit).
     * 
     * - limit (integer) A number up to 20 (the default, but is configurable) 
     *      queues to return. If not specified, it defaults to 10.
     * 
     * - echo (bool) Determines whether the API returns a client's own messages, 
     *      as determined by the uuid portion of the User-Agent header. If not 
     *      specified, echo defaults to FALSE.
     * 
     * - include_claimed (bool) Determines whether the API returns claimed 
     *      messages as well as unclaimed messages. If not specified, defaults 
     *      to FALSE (i.e. only unclaimed messages are returned).
     * 
     * @return Collection
     */
    public function listMessages(array $options = array())
    {
        // Implode array into delimeted string if necessary
        if (isset($options['ids']) && is_array($options['ids'])) {
            $options['ids'] = implode(',', $options['ids']);
        }
        
        $url = $this->url(null, $options);
        return $this->getService()->resourceList('Message', $url, $this);
    }
    
    /**
     * This operation immediately deletes the specified messages, providing a 
     * means for bulk deletes.
     * 
     * @param array $ids Two-dimensional array of IDs to be deleted
     * @return boolean
     */
    public function deleteMessages(array $ids)
    {
        $url = $this->url(null, array('ids' => implode(',', $ids)));
        $response = $this->getService()->request($url, 'DELETE');
        
        if ($response->httpStatus() != 204) {
            throw new Exception\DeleteMessageException(sprintf(
                'Could not delete this set of Queues with IDs [%s]. HTTP status '
                . '[%i] and body [%s]',
                print_r($ids, true),
                $response->httpStatus(),
                $response->httpBody()
            ));
        }
        
        return true;
    }
    
    
    /*** CLAIMS ***/
    
    /**
     * This operation claims a set of messages, up to limit, from oldest to 
     * newest, skipping any that are already claimed. If no unclaimed messages 
     * are available, FALSE is returned.
     * 
     * You should delete the message when you have finished processing it, 
     * before the claim expires, to ensure the message is processed only once. 
     * Be aware that you must call the delete() method on the Message object and
     * pass in the Claim ID, rather than relying on the service's bulk delete 
     * deleteMessages() method. This is so that the server can return an error 
     * if the claim just expired, giving you a chance to roll back your processing 
     * of the given message, since another worker will likely claim the message 
     * and process it.
     * 
     * Just as with a message's age, the age given for the claim is relative to 
     * the server's clock, and is useful for determining how quickly messages are 
     * getting processed, and whether a given message's claim is about to expire.
     * 
     * When a claim expires, it is removed, allowing another client worker to 
     * claim the message in the case that the original worker fails to process it.
     * 
     * @param int $limit
     */
    public function claimMessages($limit = 10)
    {
        $url = $this->url('claims', array('limit' => $limit));
        $response = $this->getService()->request($url, 'POST');
        
        if ($response->httpStatus() == 204) {
            return false;
        } elseif ($response->httpStatus() != 201) {
            throw new Exception\MessageException(sprintf(
                'Error claiming messages with limit [%s]. HTTP status [%i] and '
                . 'HTTP body [%s]',
                $limit,
                $response->httpStatus(),
                $response->httpBody()
            ));
        }
        
        return true;
    }
    
    /**
     * If an ID is supplied, the API is queried for a persistent object; otherwise
     * an empty object is returned.
     * 
     * @param  int $id
     * @return Claim
     */
    public function getClaim($id = null)
    {
        return $this->getService()->resource('Claim', $id)->setParent($this);
    }
    
}