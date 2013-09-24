<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http\Message;

use Guzzle\Http\Message\Request as GuzzleRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Description of Request
 * 
 * @link 
 */
class Request extends GuzzleRequest
{
    /**#@+
     * @const string METHOD constant names
     */
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_GET     = 'GET';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_TRACE   = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_PROPFIND= 'PROPFIND';
    /**#@-*/
    
    protected $expectedResponse;
    protected $exceptionHandler;
    
    public function setExpectedResponse($expectedResponse)
    {
        $this->expectedResponse = $expectedResponse;
        
        return $this;
    }
    
    public function setExceptionHandler(array $params = array())
    {
        $this->exceptionHandler = ResponseHandler::fromArray($params);
        
        return $this;
    }
    
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcher->addListener('request.complete', array(__CLASS__, 'onRequestCompletion'));
        
        return $this;
    }
    
    public function onRequestCompletion(Event $event)
    {
        if (null === $this->exceptionHandler) {
            $this->setExceptionHandler();
        }
        
        $exception = $this->exceptionHandler->setRequest($event['request'])
            ->setResponse($event['response'])
            ->setExpectedResponse($this->expectedResponse)
            ->handle();
        
        if (null !== $exception) {
            throw $exception;
        }
    }
    
}