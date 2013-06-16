<?php

namespace OpenCloud\CloudMonitoring\Resource;

class AgentHostInfo extends AgentHost
{
	
	public function SetProperty($property, $value)
	{
		$this->$property = $value;
	}

}