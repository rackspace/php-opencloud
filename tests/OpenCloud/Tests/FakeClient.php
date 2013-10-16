<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests;

use Exception;
use OpenCloud\Rackspace;
use OpenCloud\Common\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use OpenCloud\Common\Service\AbstractService;
use OpenCloud\Compute\Resource\ServerMetadata;

/**
 * Description of FakeClient
 * 
 * @link 
 */
class FakeClient extends Rackspace
{
    const DEFAULT_TYPE = 'misc';
    
    private $url;
    private $requests;
    private $responseDir;
    protected $pathType;
    protected $serviceType;
    
    public function send($requests) 
	{
        $this->serviceType = $this->traceServiceType();
        $this->responseDir = __DIR__ . DIRECTORY_SEPARATOR . 'Response' . DIRECTORY_SEPARATOR;
        $this->url = $requests->getUrl();
        $this->requests = $requests;

        $response = $this->intercept(); 

        $requests->setResponse($response);

		return $requests->getResponse();
	}
    
    public function traceServiceType()
    {
        $debug = debug_backtrace();
        foreach ($debug as $trace) {
            if (isset($trace['object'])) {
                if (method_exists($trace['object'], 'getService')) {
                    return $trace['object']->getService()->getType();
                } elseif ($trace['object'] instanceof AbstractService) {
                    return $trace['object']->getType();
                } elseif ($trace['object'] instanceof ServerMetadata) {
                    return $trace['object']->getParent()->getService()->getType();
                }
            }
        }
    }
    
	private function urlContains($substring)
	{
		return strpos($this->url, $substring) !== false;
	}

	private function covertToRegex(array $array)
	{
		$regex = array();
        array_walk($array, function($config, $urlTemplate) use (&$regex) {
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
            if (preg_match("#{$key}#", $this->url)) {
				return $item;
			}
		}
	}

    private function getBodyPath($file)
    {
        // Set to ./Response/Body/ by default
        $path = $this->responseDir . 'Body' . DIRECTORY_SEPARATOR;
        // Strip 'rax:' prefix from type - rax:autoscale becomes Autoscale
        $path .= ucfirst(str_replace('rax:', '', $this->pathType));
        // Append file path
        $path .= DIRECTORY_SEPARATOR . $file . '.json';

        return $path;
    }
    
    private function findServiceArray($array, $type) 
    {
        return isset($array[$type]) ? $array[$type] : false;
    }
        
	public function intercept()
	{
		$array = include $this->responseDir . strtoupper($this->requests->getMethod()) . '.php';

        $typeOptions = array(self::DEFAULT_TYPE, $this->serviceType);
        
        foreach ($typeOptions as $typeOption) {
            if ($serviceArray = $this->findServiceArray($array, $typeOption)) {
                if ($config = $this->matchUrlToArray($this->covertToRegex($serviceArray))) {
                    $this->pathType = $typeOption;
                    break;
                }
            }
        }

        if (empty($config)) {
            if ($this->requests->getMethod() == 'GET') { 
                return new Response(404);
            } else {
                $params = array('body' => null, 'status' => null, 'headers' => null);
            }
        } else {
            $params = $this->parseConfig($config);
        }
        
        // Set response parameters to defaults if necessary
        $body = $params['body'];
        $status = $params['status'] ?: $this->defaults('status');
        $headers = $params['headers'] ?: $this->defaults('headers');
        
        return new Response($status, $headers, $body);
	}
    
    private function parseConfig($input)
    {
        $body    = null;
        $status  = null;
        $headers = null;
        
        if (is_string($input)) {
            // A string can act as a filepath or as the actual body itself
            $bodyPath = $this->getBodyPath($input);
            
            if (file_exists($bodyPath)) {
                // Load external file contents
                $body = file_get_contents($bodyPath);
            } else{
                // Set body to string literal
                $body = $input;
            }
            
        } elseif (is_array($input)) {          

            if (isset($input['path']) || isset($input['body'])) {

                // Only one response option for this URL path
                if (isset($input['body'])) {
                    $body = $input['body'];
                } else {  
                    $bodyPath = $this->getBodyPath($input['path']);
                    if (!file_exists($bodyPath)) {
                        throw new Exception(sprintf('No response file found: %s', $bodyPath));
                    }
                    $body = file_get_contents($bodyPath);
                }
                
                if (!empty($input['status'])) {
                    $status = $input['status'];
                }
                
                if (!empty($input['headers'])) {
                    $headers = $input['headers'];
                }
                
            } elseif ($this->requests instanceof EntityEnclosingRequest) {
                // If there are multiple response options for this URL path, you
                // need to do a pattern search on the request to differentiate
                $request = (string) $this->requests;
                foreach ($input as $possibility) {
                    if (preg_match("#{$possibility['pattern']}#", $request)) {
                        return $this->parseConfig($possibility);
                    }
                }
            }
        }
        
        return array(
            'body'    => $body,
            'headers' => $headers,
            'status'  => $status
        );
    }
    
    private function defaults($key)
    {
        $config = array();
        
        switch ($this->requests->getMethod()) {
            case 'POST':
            case 'PUT':
                $config['status'] = 200;
                $config['headers'] = array();
                break;
            
            case 'GET':
                $config['status'] = 200;
                $config['headers'] = array();
                break;
            
            case 'DELETE':
                $config['status'] = 202;
                $config['headers'] = array();
                break;
            
            case 'HEAD':
                $config['status'] = 204;
                $config['headers'] = array();
                break;
            
            case 'PATCH':
                $config['status'] = 204;
                $config['headers'] = array();
                break;
            
            default:
                break;
        }
        
        return isset($config[$key]) ? $config[$key] : null;
    }
    
}