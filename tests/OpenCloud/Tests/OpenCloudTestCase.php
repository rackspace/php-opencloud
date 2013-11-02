<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests;

use PHPUnit_Framework_TestCase;
use OpenCloud\Rackspace;

class OpenCloudTestCase extends PHPUnit_Framework_TestCase
{
    
    public static $client;
    
    private static function newClient()
    {
        $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'foo',
            'apiKey'   => 'bar'
        ));
        
        $client->addSubscriber(new MockTestObserver());
        
        return $client;
    }
    
    public function getClient()
    {
        if (!self::$client) {
            self::$client = self::newClient();
        }

        return self::$client;
    }
    
    public function test_Something() {}
    
}