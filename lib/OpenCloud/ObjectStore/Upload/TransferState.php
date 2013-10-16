<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Upload;

/**
 * Description of TransferState
 * 
 * @link 
 */
class TransferState
{
    
    protected $completedParts = array();
    
    protected $running;
    
    public static function factory()
    {
        $self = new self();
        return $self->init();
    }
    
    public function getPart($key)
    {
        return $this->hasPart($key) ? $this->parts[$key] : null;
    }
    
    public function hasPart($key)
    {
        return isset($this->parts[$key]);
    }
    
    public function addPart(UploadPart $part)
    {
        $this->completedParts[] = $part;
    }
    
    public function count()
    {
        return count($this->completedParts);
    }
    
    public function isRunning()
    {
        return $this->running;
    }
    
    public function init()
    {
        $this->running = true;
        
        return $this;
    }
    
    public function cancel()
    {
        $this->running = false;
        
        return $this;
    }
    
}