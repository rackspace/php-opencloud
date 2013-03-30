<?php

namespace OpenCloud\Base;

class Debug {
	
	protected $debugState = false;
	
	public function setState($state = true) 
	{
	    $this->debugState = $state;
	}
	
	public function isEnabled()
	{
		return $this->debugState;
	}
	
}