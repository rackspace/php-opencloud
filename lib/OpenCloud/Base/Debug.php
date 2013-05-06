<?php

namespace OpenCloud\Base;

class Debug 
{
    
    protected $debugState = false;
    
    public function setDebug($state)
    {
        $this->setState($state);
    }
    
    public function setState($state = true) 
    {
        $this->debugState = $state;
    }
    
    public function isEnabled()
    {
        return $this->debugState;
    }
    
}