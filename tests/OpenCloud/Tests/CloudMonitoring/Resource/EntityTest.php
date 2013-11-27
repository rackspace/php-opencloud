<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class EntityTest extends CloudMonitoringTestCase
{

    public function testResourceClass()
    {
    	$this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Entity', $this->entity);
    }

    /**
     * @mockFile Entity_List
     */
    public function testListIsCollection()
    {
    	$this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->getEntities());
    }

}