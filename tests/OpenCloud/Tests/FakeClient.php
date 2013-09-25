<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

use OpenCloud\OpenStack;

/**
 * Description of FakeClient
 * 
 * @link 
 */
class FakeClient extends OpenStack
{
    private $url;
    private $method;
    private $responseDir;
    
    public function send($requests) 
	{   
        $this->responseDir = __DIR__ . 'Response' . DIRECTORY_SEPARATOR;
        $this->url = $requests->getUrl();
        $this->method = $requests->getMethod();
        
        $response = $this->intercept(); 
        $requests->setResponse($response);
		return $response;
	}
    
	private function urlContains($substring)
	{
		return strpos($this->url, $substring) !== false;
	}

	private function covertToRegex($array)
	{
		$regex = array();
        array_walk($array, function($config, $urlTemplate) use ($regex) {
            $trans = array(
                '{d}' => '(\d)+',
                '{s}' => '(\s)+',
                '{w}' => '(\w|\-|\.|\{|\})+',
                '/'   => '\/'
            );
            $regex[strtr($urlTemplate, $trans)] = $config;
        });
		return $regex;
	}

	private function matchUrlToArray($array)
	{
		foreach ($array as $key => $item) {
            if (preg_match("#{$key}$#", $this->url)) {
				return $item;
			}
		}
	}

    private function getBodyPath($path)
    {
        // Set to ./Response/Body/ by default
        $path = $this->responseDir . 'Body' . DIRECTORY_SEPARATOR;
        // Strip 'rax:' prefix from type - rax:autoscale becomes Autoscale
        $path .= ucfirst(str_replace('rax:', '', $this->getServiceType()));
        // Append file path
        $path .= DIRECTORY_SEPARATOR . $path . '.json';
        
        return $path;
    }
    
    private function findServiceArray($array) 
    {
        $type = $this->getServiceType();
        
        if (!array_key_exists($type, $array)) {
            throw new Exception(sprintf(
                '%s service was not found in the response array template.',
                $type
            ));
        }
        return $array[$type];
    }
    
	public function intercept()
	{
		$array = include $this->responseDir . strtoupper($this->method) . '.php';

        // Retrieve second-level array from service type
        $serviceArray = $this->findServiceArray($array);
        
        // Now find the config array based on the path
        if (!$config = $this->matchUrlToArray($this->covertToRegex($serviceArray))) {
            // If not found, assume a 404
            return new Response(404);
        }
        
        // If no path is set, assume it's an empty body
        if (empty($config['path'])) {
           $body = null;
        } else {
            // Retrieve file contents for body
            $bodyPath = $this->getBodyPath($config['path']);
            if (!file_exists($bodyPath)) {
                throw new Exception(sprintf('No response file found: %s', $bodyPath));
            }
            $body = include $bodyPath;
        }
        
        // Set defaults if none explicitly provided
        $statusCode = (!empty($config['status'])) ? $config['status'] : $this->defaults('status');
        $headers = (!empty($config['headers'])) ? $config['headers'] : $this->defaults('headers');
        
        return new Response($statusCode, $headers, $body);
	}
    
    private function defaults($key)
    {
        $config = array();
        
        switch ($this->method) {
            case 'POST':
            case 'PUT':
                $config['status'] = 101;
                $config['headers'] = array();
                break;
            
            case 'GET':
                $config['status'] = 101;
                $config['headers'] = array();
                break;
            
            case 'HEAD':
                $config['status'] = 101;
                $config['headers'] = array();
                break;
            
            case 'PATCH':
                $config['status'] = 101;
                $config['headers'] = array();
                break;
            
            default:
                break;
        }
        
        return isset($config[$key]) ? $config[$key] : null;
    }
    
}