<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * ReadonlyResource class.
 * 
 * @extends AbstractResource
 */
class ReadonlyResource extends AbstractResource
{
    
    public function create($params = array()) 
    { 
        return $this->noCreate(); 
    }

    public function update($params = array()) 
    { 
        return $this->noUpdate(); 
    }

    public function delete($params = array()) 
    { 
        return $this->noDelete(); 
    }

}