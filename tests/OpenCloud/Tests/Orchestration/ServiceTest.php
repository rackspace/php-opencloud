<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Orchestration;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Exceptions\ServiceValueError;
use OpenCloud\Tests\StubConnection;

class ServiceTest extends PHPUnit_Framework_TestCase 
{
    private $service;
    
    public function __construct()
    {
        $connection = new StubConnection('http://example.com', 'SECRET');
        //$this->service = $connection->orchestration();
    }
    
    public function testServiceClass()
    {
        //$this->assertInstanceOf('OpenCloud\Orchestration\Service', $this->service);
    }

}