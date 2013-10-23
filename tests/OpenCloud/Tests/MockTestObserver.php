<?php

namespace OpenCloud\Tests;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use OpenCloud\Common\Http\Message\EntityEnclosingRequest;
use OpenCloud\Common\Service\AbstractService;
use OpenCloud\Compute\Resource\ServerMetadata;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of FakeClient
 * 
 * @link 
 */
class MockTestObserver implements EventSubscriberInterface
{
    const DEFAULT_TYPE = 'misc';
    
    private $request;
    private $responseDir;
    protected $pathType;
    protected $serviceType;
    
    public function __construct()
    {
        $this->responseDir = __DIR__ . DIRECTORY_SEPARATOR . 'Response' . DIRECTORY_SEPARATOR;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => 'onBeforeSend'
        );
    }
    
    public function onBeforeSend(Event $event)
    {
        $this->request = $event['request'];
        $this->serviceType = $this->traceServiceType();

        $event['request']->setResponse($this->produceMockResponse())
            ->setState(Request::STATE_COMPLETE);
    }
    
    public function traceServiceType()
    {
        $host = (string) $this->request->getUrl(true)->getHost();
        
        $json = json_decode(file_get_contents($this->responseDir . 'Body/Misc/tokens.json'));
        
        foreach ($json->access->serviceCatalog as $service) {
            foreach ($service->endpoints as $endpoint) {
                if ((isset($endpoint->publicURL) && strpos($endpoint->publicURL, $host) !== false)
                    || (isset($endpoint->internalURL) && strpos($endpoint->internalURL, $host) !== false)
                ) {
                    return $service->type;
                }
            }
        }
    }
    
	private function urlContains($substring)
	{
		return strpos($this->request->getUrl(), $substring) !== false;
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
            if (preg_match("#{$key}#", $this->request->getUrl())) {
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
        
	public function produceMockResponse()
	{
		$array = include $this->responseDir . strtoupper($this->request->getMethod()) . '.php';

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
            if ($this->request->getMethod() == 'GET') { 
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
                        throw new \Exception(sprintf('No response file found: %s', $bodyPath));
                    }
                    $body = file_get_contents($bodyPath);
                }
                
                if (!empty($input['status'])) {
                    $status = $input['status'];
                }
                
                if (!empty($input['headers'])) {
                    $headers = $input['headers'];
                }
                
            } elseif ($this->request instanceof EntityEnclosingRequest) {
                // If there are multiple response options for this URL path, you
                // need to do a pattern search on the request to differentiate
                $request = (string) $this->request;
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
        
        switch ($this->request->getMethod()) {
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