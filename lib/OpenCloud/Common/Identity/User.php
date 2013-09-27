<?php
/**
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 * @package phpOpenCloud
 * @version 1.5.9
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.co.uk>
 */

namespace OpenCloud\Common\Identity;

/**
 * Represents a sub-user in Keystone.
 *
 * @link http://docs.rackspace.com/auth/api/v2.0/auth-client-devguide/content/User_Calls.html
 * 
 * @codeCoverageIgnore
 */
class User
{
    
    public static function factory($info)
    {
        $user = new self;
    }
    
    /**
     * Return detailed information about a specific user, by either user name or user ID.
     * @param int|string $info
     */
    public function get($info)
    {
    }
    
    public function create()
    {
    }
    
    public function update()
    {
    }
    
    public function delete()
    {
    }
    
    public function listAllCredentials()
    {
    }
    
    public function getCredentials()
    {
    }
    
    public function resetApiKey()
    {
    }
    
}
