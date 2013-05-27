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

		$this->response->body = $this->$method($url);
	}

	private function urlContains($substring)
	{
		return strpos($this->url, $substring) !== false;
	}

	public function doGet()
	{
		if ($this->urlContains('limits')) {
			return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('account')) {
			return $this->externalFile('Account', 'account');
		} elseif ($this->urlContains('audits')) {
			return $this->externalFile('Account', 'audit_list');
		} elseif ($this->urlContains('usage')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('entities')) {
			return $this->externalFile('Entity', 'entities');
		} elseif ($this->urlContains('entities/\d')) {
			return $this->externalFile('Entity', 'entity');
		} elseif ($this->urlContains('entities/\d/checks')) {
			return $this->externalFile('Check', 'list_checks');
		} elseif ($this->urlContains('entities/\d/checks/\d')) {
			return $this->externalFile('Check', 'get');
		} elseif ($this->urlContains('entities/\d/checks/\d/metrics')) {
			return $this->externalFile('Metric', 'list');
		} elseif ($this->urlContains('entities/\d/checks/\d/metrics/\s')) {
			return $this->externalFile('Metric', 'data_points');
		} elseif ($this->urlContains('check_types')) {
			return $this->externalFile('CheckType', 'list');
		} elseif ($this->urlContains('check_types/\d')) {
			return $this->externalFile('CheckType', 'get');
		} elseif ($this->urlContains('monitoring_zones/\d/traceroute')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('entities/\d/alarms')) {
			//return $this->externalFile('Alarm', 'limits');
		} elseif ($this->urlContains('entities/\d/alarms/\d')) {
			//return $this->externalFile('Alarm', 'get');
		} elseif ($this->urlContains('entities/\d/alarms/alarmId/notification_history')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('entities/\d/alarms/alarmId/notification_history/\d')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('entities/\d/alarms/alarmId/notification_history/\d/uuid')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('notifications')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('notifications/\d')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('notification_types')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('notification_types/\d')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('changelogs/alarms')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('changelogs/alarms?entityId=\d')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('views/overview')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('views/overview?id=entityId&id=\d')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('views/overview?uri=\s&uri=\s')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('alarm_examples')) {
			//return $this->externalFile('Account', 'limits');
		} elseif ($this->urlContains('alarm_examples/\d')) {
			//return $this->externalFile('Account', 'limits');
		} 
	}

	public function doPost()
	{
		if ($this->urlContains('')) {
			
		}
	}

}