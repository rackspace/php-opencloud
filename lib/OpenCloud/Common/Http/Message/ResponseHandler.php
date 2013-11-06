<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http\Message;

use OpenCloud\Common\Http\Exception\UnexpectedResponseException;
use OpenCloud\Common\Http\Exception\BadResponseException;

/**
 * Description of ResponseHandler
 * 
 * @link 
 */
class ResponseHandler
{
    private $template = array();
    
    public static function fromArray(array $params = array())
    {
        $self = new self();
        $self->setConfiguration($params);
        return $self;
    }
    
    public function setConfiguration(array $params = array())
    {
        foreach ($params as $status => $config) {
            if (Response::isValidStatus($status)) {
                $this->template[$status] = $config;
            }
        }
        
        return $this;
    }
    
    public function setRequest($request)
    {
        $this->request = $request;
        
        return $this;
    }
    
    public function setResponse($response)
    {
        $this->response = $response;
        
        return $this;
    }
    
    public function setExpectedResponse($expected)
    {
        $this->expectedResponse = $expected;
        
        return $this;
    }
    
    public function handle()
    {
        $status = $this->response->getStatusCode();
        
        // If somebody is expecting a specific response code, make the check stricter
        if ($this->expectedResponse) {
            
            $match = false;

            if (is_array($this->expectedResponse) && in_array($status, $this->expectedResponse)) {
                $match = true;
            } elseif ((int) $this->expectedResponse == $status) {
                $match = true;
            }
            
            if (!$match) {
                return UnexpectedResponseException::factory($this->request, $this->response);
            }
        }

        // @codeCoverageIgnoreStart
        if (in_array($status, $this->template)) {

            $config = $this->template[$status];
            if (isset($config['allow']) && $config['allow'] === true) {
                if (!empty($config['callback'])) {
                    return $config['callback'];
                }
            } else {

                $class = empty($config['class']) ? 'BadResponseException' : $config['class'];
                return new $class($config['message']);
            }

        } elseif ($this->response->isError()) {
            // Otherwise, handle other errors
            return BadResponseException::factory($this->request, $this->response);
        }
        // @codeCoverageIgnoreEnd
    }
    
}