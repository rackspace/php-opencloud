<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\OpenStack;
use OpenCloud\Common\Request\Response\Blank;

class FakeConnection extends OpenStack
{
	private $response;
	private $testDir;
    
    public $realRequests = false;
    
	public function __construct($url, $secret, $options = array()) 
	{
		$this->testDir = __DIR__;

		if (is_array($secret)) {
			return parent::__construct($url, $secret, $options);
		} else {
			return parent::__construct(
				$url,
				array(
					'username' => 'X', 
					'password' => 'Y'
				), 
				$options
			);
		}
	}
    
	public function Request($url, $method = "GET", $headers = array(), $body = null) 
	{   
                if ($this->realRequests && !preg_match('#/tokens$#', $url)) {
                    return parent::Request($url, $method, $headers, $body);
                }
                if(is_array($url)) {
                    foreach($url as &$oneUrl) {
                        $oneUrl = trim($oneUrl, '/');
                    }
                    $this->url = $url;
                } else {
                    $this->url = trim($url, '/');
                }
		$response = new Blank;
		$response->headers = array('Content-Length' => '999');

		switch ($method) {
			case 'POST':
				$method = 'doPost'; 
				break;
			default:
			case 'GET':
				$method = 'doGet';
				break;
			case 'DELETE':
				$method = 'doDelete';
				break;
		}

		$response->body = $this->$method($url);

		return $response;
	}

	private function urlContains($substring)
	{
		return strpos($this->url, $substring) !== false;
	}

	private function covertToRegex($array)
	{
		$new = array();
		foreach ($array as $key => $item) {
			$value = str_replace('{d}', '(\d)+', $key);
			$value = str_replace('{s}', '(\s)+', $value);
			$value = str_replace('{w}', '(\w|\-|\.)+', $value);
			$value = str_replace('/', '\/', $value);
			$new[$value] = $item;
		}
		return $new;
	}

	private function matchUrlToArray($array)
	{
		foreach ($array as $key => $item) {
			$pattern = "#{$key}$#"; 
                        if(is_array($this->url)) {
                            foreach($this->url as $url) {
                                $result = $this->compareUrlToRegex($pattern, $url, $item);
                                if($result) {
                                    return $result;
                                }
                            }
                        } else {
                            $result = $this->compareUrlToRegex($pattern, $this->url, $item);
                            if($result) {
                                return $result;
                            }
                        }
		}
	}
        
        private function compareUrlToRegex($pattern, $url, $item) {
            if (preg_match($pattern, $url)) {
                    $path = __DIR__ . "/Resource/{$item}.json";
                    if (file_exists($path)) {
                            return file_get_contents($path);
                    }
            }
            return null;
        }

	public function doGet()
	{
		$array = include 'Resource/GetResponses.php';
		$array = $this->covertToRegex($array);
		return $this->matchUrlToArray($array);
	}

	public function doPost()
	{
		$array = include 'Resource/PostResponses.php';
		$array = $this->covertToRegex($array);
		return $this->matchUrlToArray($array);
	}

}