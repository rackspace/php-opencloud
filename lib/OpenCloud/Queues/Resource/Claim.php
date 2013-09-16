<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Queues\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Exceptions\UpdateError;

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
    protected $id;
    
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
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setAge($age)
    {
        $this->age = $age;
        return $this;        
    }
    
    public function getAge()
    {
        return $this->age;
    }
    
    public function getGrace()
    {
        return $this->grace;
    }
    
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }
    
    public function getMessages()
    {
        return $this->messages;
    }
    
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }
    
    public function getTtl()
    {
        return $this->ttl;
    }
    
    public function setHref($href)
    {
        $paths = explode('/', $href);
        $this->id = end($paths);
        $this->href = $href;
        
        return $this;
    }
    
    public function getHref()
    {
        return $this->href;
    }
    
    /**
     * {@inheritDoc}
     */
    public function create($params = array())
    {
        return $this->noCreate();
    }
    
    /**
     * Updates the current Claim. It is recommended that you periodically renew 
     * claims during long-running batches of work to avoid loosing a claim in 
     * the middle of processing a message. This is done by setting a new TTL for 
     * the claim (which may be different from the original TTL). The server will 
     * then reset the age of the claim and apply the new TTL.
     */
    public function update($params = array())
    {
        $object = (object) array(
            'grace' => $this->getGrace(), 
            'ttl'   => $this->getTtl()
        );

        $json = json_encode($object);
        $this->checkJsonError();

        $response = $this->getService()->request($this->url(), 'PATCH', array(), $json);

        if ($response->httpStatus() != 204) {
            throw new UpdateError(sprintf(
                'Error updating [%s] with [%s], status [%d] response [%s]',
                get_class($this),
                $json,
                $response->HttpStatus(),
                $response->HttpBody()
            ));
        }

        return true;
    }
    
}