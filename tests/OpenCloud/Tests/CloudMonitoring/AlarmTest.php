<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class AlarmTest extends OpenCloudTestCase
{

    const ENTITY_ID = 'enAAAAA';
    const ALARM_ID  = 'alAAAA';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');
        
        // Parent object (i.e. entity)
        $entityResource = $this->service->resource('Entity');
        $entityResource->refresh(self::ENTITY_ID);
        
        $this->resource = $this->service->resource('Alarm');
        $this->resource->setParent($entityResource);
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Alarm',
            $this->resource
        );
    }
    
    public function testParentClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Entity',
            $this->resource->getParent()
        );
    }
    
    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/entities/'.self::ENTITY_ID.'/alarms',
            $this->resource->url()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithNoParams()
    {
        $this->resource->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFailsWithNoParams()
    {
        $this->resource->update();
    }
    
    public function testAlarmTesting()
    {
        $params = array();
        
        // Set criteria
        $params['criteria'] = 'if (metric["code"] == "404") { return new AlarmStatus(CRITICAL, "not found"); } return new AlarmStatus(OK);';
        
        // Data which needs to be tested
        $params['check_data'] = json_decode(file_get_contents(__DIR__ . '/../Response/Body/Monitor/Check/test_existing.json'));
        
        $response = $this->resource->test($params);

        $this->assertObjectHasAttribute('timestamp', $response[0]);
        $this->assertObjectHasAttribute('status', $response[0]);
        
        $this->assertEquals('OK', $response[0]->state);
    }
    
    public function testAlarmCollection()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
    }
    
    public function testGetAlarm()
    {
        $this->resource->refresh(self::ALARM_ID);
        
        $this->assertEquals($this->resource->getId(), self::ALARM_ID);
        $this->assertEquals($this->resource->getParent()->getId(), self::ENTITY_ID);
        
        $this->expectOutputRegex('/return new AlarmStatus\(OK\)/');
        echo $this->resource->getCriteria();
    }
    
    public function testCreate()
    {
        $this->resource->create(array(
            'check_id'             => 'foo',
            'notification_plan_id' => 'bar'
        ));
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AlarmException
     */
    public function testTestWithoutCriteriaParamFails()
    {
        $this->resource->test();
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AlarmException
     */
    public function testTestWithoutRequiredParamsFails()
    {
        $this->resource->test(array('criteria' => 'foobar'));
    }
    
}