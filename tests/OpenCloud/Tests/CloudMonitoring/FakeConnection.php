<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\OpenStack;
use OpenCloud\Common\Request\Response\Blank;

class FakeConnection extends OpenStack
{
	private $response;
	
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
		$this->url = $url;

		$this->response = new Blank;
		$this->response->headers = array('Content-Length' => '999');

		switch ($method) {
			case 'POST':
				$method = 'initPost'; 
				break;
			default:
			case 'GET':
				$method = 'initGet';
				break;
			case 'DELETE':
				$method = 'initDelete';
				break;
		}

		$this->response->body = $this->$method($url);
	}

	private function urlContains($substring)
	{
		return strpos($this->url, $substring) !== false;
	}

	public function initPost()
	{
		if ($this->urlContains('')) {
			
		}
	}

}