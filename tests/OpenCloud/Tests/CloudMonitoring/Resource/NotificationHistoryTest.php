<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class NotificationHistoryTest extends CloudMonitoringTestCase
{
    
    const ENTITY_ID = 'enAAAAA';
    const ALARM_ID  = 'alAAAA';
    const CHECK_ID  = 'chAAAA';
    const NH_ID     = '646ac7b0-0b34-11e1-a0a1-0ff89fa2fa26';

    private $alarm;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->getTestFilePath('Alarm'));
        $this->alarm = $this->entity->getAlarm(self::ALARM_ID);
    }

    public function test_Record_Checks()
    {
        $response = new \Guzzle\Http\Message\Response(200, array('Content-Type' => 'application/json'), '{"check_ids": ["chOne","chTwo"]}');
        $this->addMockSubscriber($response);

        $checks = $this->alarm->getRecordedChecks();

        $this->assertCount(2, $checks);
        $this->assertEquals('chOne', $checks[0]);
    }

    /**
     * @mockFile NH_List
     */
    public function test_Check_History()
    {
        $list = $this->alarm->getNotificationHistoryForCheck(self::CHECK_ID);

        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);

        $first = $list->first();
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\NotificationHistory',
            $first
        );
        
        $this->assertEquals('sometransaction', $first->getTransactionId());
        $this->assertEquals('matched return statement on line 6', $first->getStatus());
    }

    /**
     * @mockFile NH_Item
     */
    public function test_Single()
    {
        $item = $this->alarm->getNotificationHistoryItem(self::CHECK_ID, self::NH_ID);

        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\NotificationHistory',
            $item
        );

        $this->assertEquals(self::NH_ID, $item->getId());
        $this->assertEquals(1320885544875, $item->getTimestamp());
        $this->assertEquals('WARNING', $item->getState());
    }
    
}