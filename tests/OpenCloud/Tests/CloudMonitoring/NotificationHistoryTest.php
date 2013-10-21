<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class NotificationHistoryTest extends OpenCloudTestCase
{
    
    const ENTITY_ID = 'enAAAAA';
    const ALARM_ID  = 'alAAAA';
    const CHECK_ID  = 'chAAAA';
    const NH_ID     = '646ac7b0-0b34-11e1-a0a1-0ff89fa2fa26';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');
        
        // Grandparent resource (i.e. entity)
        $entityResource = $this->service->resource('entity');
        $entityResource->refresh(self::ENTITY_ID);
        
        // Parent resource (i.e. alarm)
        $alarmResource = $this->service->resource('alarm');
        $alarmResource->setParent($entityResource);
        $alarmResource->refresh(self::ALARM_ID);
    
        // This resource
        $this->resource = $this->service->resource('NotificationHistory');
        $this->resource->setParent($alarmResource);
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\NotificationHistory',
            $this->resource
        );
    }
    
    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/entities/'.self::ENTITY_ID.'/alarms/'.self::ALARM_ID.'/notification_history',
            $this->resource->Url()
        );
    }
    
    public function testListChecks()
    {
        $response = $this->resource->listChecks();
        $checkIds = $response->check_ids;
        $this->assertCount(2, $checkIds);
        $this->assertEquals('chOne', $checkIds[0]);
    }
    
    public function testListHistory()
    {
        $list = $this->resource->listHistory(self::CHECK_ID);
        
        $first = $list->first();
        
        $this->assertEquals('sometransaction', $first->getTransactionId());
        $this->assertEquals('matched return statement on line 6', $first->getStatus());
    }
    
    public function testSingle()
    {
        $this->resource->getSingleHistoryItem(self::CHECK_ID, self::NH_ID);
        
        $this->assertEquals(self::NH_ID, $this->resource->getId());
        $this->assertEquals(1320885544875, $this->resource->getTimestamp());
        $this->assertEquals('WARNING', $this->resource->getState());
        
    }
    
}