<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

/**
 * Represents a sub-user in Keystone.
 *
 * @link http://docs.rackspace.com/auth/api/v2.0/auth-client-devguide/content/User_Calls.html
 * 
 * @codeCoverageIgnore
 */
class User extends PersistentObject
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
        if (is_integer($info)) {
            
        } elseif (is_string($info)) {
            
        } else {
            throw new Exception\IdentityException(sprintf(
                'A string-based username or an integer-based user ID is valid'
            ));
        }
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