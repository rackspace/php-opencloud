<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class CheckTest extends OpenCloudTestCase
{
    const ENTITY_ID = 'enAAAA';

    private $entity;

    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');

        $this->entity = $this->service->getEntity(self::ENTITY_ID);
        $this->resource = $this->entity->getCheck();
    }
    
    public function test_Create()
    {
        $this->entity->createCheck(array(
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

        $response = $this->resource->testParams(array(), false);

        $this->assertNotNull($response[0]);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
    }

    public function test_Check_Test_With_Debug()
    {
        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->testParams(array(), true);
        $this->assertObjectHasAttribute('debug_info', $response[0]);
    }

    public function test_Existing_Check_Test()
    {
        $this->resource->setId('chAAAA');
        $this->resource->setType('remote.http');

        $response = $this->resource->test();
        
        $this->assertObjectHasAttribute('metrics', $response[0]);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
    }

    public function test_Get_Check()
    {
        $this->resource->refresh('chAAAA');

        $this->assertEquals($this->resource->getId(), 'chAAAA');
    }
    
}