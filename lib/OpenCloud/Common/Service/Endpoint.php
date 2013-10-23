<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Service;

use Guzzle\Http\Url;

/**
 * Description of Endpoint
 * 
 * @link 
 */
class Endpoint
{
    
    private $publicUrl;
    private $region;
    private $privateUrl;
    
    public static function factory($object)
    {
        $endpoint = new self();
        
        if (isset($object->publicURL)) {
            $endpoint->setPublicUrl($object->publicURL);
        }
        if (isset($object->internalURL)) {
            $endpoint->setPrivateUrl($object->internalURL);
        }
        if (isset($object->region)) {
            $endpoint->setRegion($object->region);
        }

        return $endpoint;
    }
    
    public function setPublicUrl($publicUrl)
    {
        $this->publicUrl = Url::factory($publicUrl);
        return $this;
    }
    
    public function getPublicUrl()
    {
        return $this->publicUrl;
    }
    
    public function setPrivateUrl($privateUrl)
    {
        $this->privateUrl = Url::factory($privateUrl);
        return $this;
    }
    
    public function getPrivateUrl()
    {
        return $this->privateUrl;
    }
    
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }
    
    public function getRegion()
    {
        return $this->region;
    }
    
}