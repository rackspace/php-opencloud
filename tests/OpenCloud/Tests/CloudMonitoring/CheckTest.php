<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class CheckTest extends OpenCloudTestCase
{

    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');

        $parentEntity = $this->service->resource('entity', 'enAAAAA');
        
        $this->resource = $this->service->resource('check');
        $this->resource->setParent($parentEntity);
    }

    public function testCheckClass()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\Check', $this->resource);
    }

    public function testListAllIsCollection()
    {
        $this->assertInstanceOf('OpenCloud\\Common\\Collection', $this->resource->listAll());
    }

    public function testParentClass()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\Entity', $this->resource->getParent());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithoutRequiredParams()
    {
        $this->resource->create();
    }

    public function testCheckTest()
    {
        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->test(array(), false);

        $this->assertNotNull($response);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
    }

    public function testCheckTestWithDebug()
    {
        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->test(array(), true);
        $this->assertObjectHasAttribute('debug_info', $response[0]);
    }

    public function testExistingCheckTest()
    {
        $this->resource->setId('chAAAA');
        $this->resource->setType('remote.http');

        $response = $this->resource->testExisting();
        $this->assertNotNull($response);

        $this->assertObjectHasAttribute('metrics', $response[0]);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
    }

    public function testGetCheck()
    {
        $this->resource->refresh('chAAAA');

        $this->assertEquals($this->resource->getId(), 'chAAAA');
    }
    
}