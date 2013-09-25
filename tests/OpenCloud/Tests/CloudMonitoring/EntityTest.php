<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;
use OpenCloud\Common\Collection;

class EntityTest extends OpenCloudTestCase
{
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('entity');
    }

    public function testResourceClass()
    {
    	$this->assertEquals(
    		get_class($this->resource),
    		'OpenCloud\\CloudMonitoring\\Resource\\Entity'
    	);
    }

    public function testListIsCollection()
    {
    	$this->assertTrue(
    		$this->resource->listAll() instanceof Collection
    	);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testEntityCreateFailIfNoLabel()
    {
    	$this->resource->Create();
    }

}