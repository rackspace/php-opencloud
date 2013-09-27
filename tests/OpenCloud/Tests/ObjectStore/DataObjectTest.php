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

namespace OpenCloud\Tests\ObjectStore;

class DataObjectTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $dataobject;
    private $service;
    private $container;
    private $nullFile; 
    private $nonCDNContainer;

    public function __construct()
    {
        $this->service = $this->getClient()->objectStore('cloudFiles', 'DFW');
        $this->container = $this->service->container('TEST');
        $this->dataobject = $this->container->dataObject('DATA-OBJECT');

        $this->nullFile = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'NUL' : '/dev/null';
        
        $this->nonCDNContainer = $this->service->container('NON-CDN');
        $this->nonCDNObject = $this->nonCDNContainer->dataObject('OBJECT');
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertEquals('DATA-OBJECT', $this->dataobject->name);
        $this->assertInstanceOf('OpenCloud\Common\Metadata', $this->dataobject->metadata);
    }
}
