<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Http\Message;

use OpenCloud\Http\Exception\UnexpectedResponseException;

/**
 * Description of ResponseHandler
 * 
 * @link 
 */
class ResponseHandler
{
    
    public static function fromArray(array $params = array())
    {
        $self = new self();
        $self->setConfiguration($params);
        return $self;
    }
    
    public function setConfiguration(array $params = array())
    {
        foreach ($params as $status => $config) {
            if (Request::isValidStatus($status)) {
                $this->template[$status] = $config;
            }
        }
    }
    
    public function setRequest($request)
    {
        $this->request = $request;
    }
    
    public function setResponse($response)
    {
        $this->response = $response;
    }
    
    public function setExpectedResponse($expected)
    {
        $this->expectedResponse = $expected;
    }
    
    public function handle()
    {
        $status = $this->response->getStatus();
        
        // If somebody is expecting a specific response code, make the check stricter
        if ($this->expectedResponse && $this->expectedResponse != $this->response) {
            return new UnexpectedResponseException(sprintf(
                'This operation was expecting a %d status code, but received %d',
                $this->expectedResponse,
                $status
            ));
        }
        
        if (in_array($status, $this->template)) {
            // Throw custom message
            $config = $this->template[$status];
            $class = $config['class'];
            return new $class($config['message']);
        } else {
            // Otherwise, rely on Guzzle's native error handling
            return BadResponseException::factory($this->request, $this->response);
        }
    }
    
}