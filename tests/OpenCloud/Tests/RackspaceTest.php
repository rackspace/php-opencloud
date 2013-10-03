<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests;

use PHPUnit_Framework_TestCase;
use OpenCloud\Rackspace;
use OpenCloud\Common\Request\Response\Blank;

/**
 * Stub for Rackspace to Override the ->Request() method
 */
class MyRackspace extends Rackspace
{

    public function Request($url, $method = 'GET', $headers = array(), $data = NULL)
    {
        return new Blank(array(
            'body' => file_get_contents(TESTDIR . '/connection.json')
        ));
    }

}

class RackspaceTest extends PHPUnit_Framework_TestCase
{

    private $conn;

    public function __construct()
    {
        $this->conn = new MyRackspace('http://example.com', array(
            'username' => 'FOO', 
            'apiKey'   => 'BAR'
        ));
    }

    /**
     * Tests
     */
    public function testCredentials()
    {
        $this->assertRegExp('/"username": "FOO"/', $this->conn->Credentials());
        
        $this->assertRegExp(
            '/RAX-KSKEY:apiKeyCredentials/', 
            $this->conn->Credentials()
        );
    }

    public function testDbService()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Service', 
            $this->conn->dbService(null, array('DFW'))
        );
    }

    public function testLoadBalancerService()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Service', 
            $this->conn->loadBalancerService(null, array('DFW'))
        );
    }

    public function testDNS()
    {
        $this->assertInstanceOf(
            'OpenCloud\DNS\Service', 
            $this->conn->DNS(null, array('DFW'))
        );
    }

    public function testCloudMonitoring()
    {
        $this->assertInstanceOf(
            'OpenCloud\CloudMonitoring\Service', 
            $this->conn->cloudMonitoring()
        );
    }

    public function testAutoscale()
    {
        $this->assertInstanceOf(
            'OpenCloud\Autoscale\Service', 
            $this->conn->autoscale(null, array('DFW'))
        );
    }
    
    public function testQueues()
    {
        $this->assertInstanceOf(
            'OpenCloud\Queues\Service', 
            $this->conn->queues(null, array('ORD'))
        );
    }

}
