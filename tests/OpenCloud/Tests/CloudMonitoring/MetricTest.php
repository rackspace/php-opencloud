<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class MetricTest extends OpenCloudTestCase
{
    
    const ENTITY_ID   = 'enAAAAA';
    const CHECK_ID    = 'chAAAA';
    const METRIC_NAME = 'mzdfw.available';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');

        $entity = $this->service->getEntity(self::ENTITY_ID);
        $this->check = $entity->getCheck(self::CHECK_ID);
        $this->metrics = $this->check->getMetrics();
        $this->resource = $this->service->resource('Metric')->setParent($this->check);
    }

    public function testResourceClass()
    {
    	$this->assertEquals(
    		get_class($this->resource),
    		'OpenCloud\\CloudMonitoring\\Resource\\Metric'
    	);
    }

    public function testListIsCollection()
    {
    	$this->assertInstanceOf(
    	   'OpenCloud\\Common\\Collection',
    	   $this->metrics
    	);
    }
    
    public function testParentClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Check',
            $this->resource->getParent()
        );
    }
    
    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/entities/'.self::ENTITY_ID.'/checks/'.self::CHECK_ID.'/metrics',
            (string) $this->resource->getUrl()
        );
    }
    
    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFail()
    {
    	$this->resource->create();
    }
   
    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */ 
    public function testUpdateFail()
    {
        $this->resource->update();
    }
    
    /**
     * @expectedException \OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDeleteFail()
    {
        $this->resource->delete();
    }
    
    public function testDataPointsClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->check->fetchDataPoints(self::METRIC_NAME, array(
            	'resolution' => 'FULL',
                'select'     => 'average',
            	'from'       => 1369756378450,
            	'to'         => 1369760279018
            ))
        );
    }
    
    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithoutToFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME);
    }
    
    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithoutFromFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to' => 1369760279018
        ));
    }
    
    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithoutTheRestFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to'     => 1369760279018,
            'from'   => 1369756378450
        ));
    }
    
    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithIncorrectSelectFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to'     => 1369760279018,
            'from'   => 1369756378450,
            'select' => 'foo'
        ));
    }
    
    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithIncorrectResolutionFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to'         => 1369760279018,
            'from'       => 1369756378450,
            'resolution' => 'bar'
        ));
    }

}