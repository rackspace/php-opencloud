<?php

/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */
namespace OpenCloud\Common\Http\Message;

use Guzzle\Http\Message\EntityEnclosingRequest as GuzzleEntityEnclosingRequest;
use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Guzzle\Http\Exception\RequestException;

/**
 * Description of EntityEnclosingRequest
 * 
 * @link 
 */
class EntityEnclosingRequest extends GuzzleEntityEnclosingRequest
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
        $this->eventDispatcher->addListener('request.complete', array($this, 'onRequestCompletion'));

        return $this;
    }
    
    public function onRequestCompletion(Event $event)
    {
        $this->setResponse(Response::fromParent($event['response']));
        
        if (null === $this->exceptionHandler) {
            $this->setExceptionHandler();
        }
        
        $handlerResponse = $this->exceptionHandler->setRequest($event['request'])
            ->setResponse($event['response'])
            ->setExpectedResponse($this->expectedResponse)
            ->handle();
        
        if ($handlerResponse instanceof RequestException) {
            throw $handlerResponse;
        } elseif (is_callable($handlerResponse)) {
            // @codeCoverageIgnoreStart
            return call_user_func($handlerResponse, $event['response']);
            // @codeCoverageIgnoreEnd
        }
    }
    
}