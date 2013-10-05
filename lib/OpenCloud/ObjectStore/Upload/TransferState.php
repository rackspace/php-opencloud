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
    
    public function addPart(UploadPart $part)
    {
        $this->completedParts[] = $part;
    }
    
    public function count()
    {
        return count($this->completedParts);
    }
    
}