<?php

/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */
namespace OpenCloud\ObjectStore\Resource;

/**
 * Description of Account
 * 
 * @link 
 */
class Account extends AbstractResource
{
    const METADATA_LABEL = 'Account';
    
    private $tempUrlSecret;
    
    public function getUrl($path = null, array $query = array())
    {
        return $this->getService()->getUrl();
    }
    
    public function getDetails()
    {
        return $this->retrieveMetadata();
    }
    
    public function getObjectCount()
    {
        return $this->metadata->getProperty('Object-Count');
    }
    
    public function getContainerCount()
    {
        return $this->metadata->getProperty('Container-Count');
    }
    
    public function getBytesUsed()
    {
        return $this->metadata->getProperty('Bytes-Used');
    }
    
    /** 
     * Sets the shared secret value for the TEMP_URL
     *
     * @param string $secret the shared secret
     * @return HttpResponse
     */
    public function setTempUrlSecret($secret = null) 
    {
        if (!$secret) {
            $secret = sha1(rand(1, 99999));
        }
        
        $this->tempUrlSecret = $secret;
        
        $this->saveMetadata($this->appendToMetadata(array('Temp-Url-Key' => $secret)));
        
        return $this;
    }
    
    public function getTempUrlSecret()
    {
        if (null === $this->tempUrlSecret) {
            $this->retrieveMetadata();
            $this->tempUrlSecret = $this->metadata->getProperty('Temp-Url-Key');
        }
        
        return $this->tempUrlSecret;
    }
    
}