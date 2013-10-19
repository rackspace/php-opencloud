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
    
    public function test_Create()
    {
        $this->service->resource('check')->create(array(
            'type' => 'webhook',
            'name' => 'TEST'
        ));
    }
    
    public function test_Check_Class()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\Check', $this->resource);
    }

    public function test_List_All_Is_Collection()
    {
        $this->assertInstanceOf('OpenCloud\\Common\\Collection', $this->resource->listAll());
    }

    public function test_Parent_Class()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\Entity', $this->resource->getParent());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Create_Fails_Without_Required_Params()
    {
        $this->resource->create();
    }

    public function test_Check_Test()
    {
        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->test(array(), false);

        $this->assertNotNull($response);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
    }

    public function test_Check_Test_With_Debug()
    {
        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->test(array(), true);
        $this->assertObjectHasAttribute('debug_info', $response[0]);
    }

    public function test_Existing_Check_Test()
    {
        $this->resource->setId('chAAAA');
        $this->resource->setType('remote.http');

        $response = $this->resource->testExisting();
        
        $this->assertObjectHasAttribute('metrics', $response[0]);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
    }

    public function test_Get_Check()
    {
        $this->resource->refresh('chAAAA');

        $this->assertEquals($this->resource->getId(), 'chAAAA');
    }
    
}