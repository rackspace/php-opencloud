<?php

namespace OpenCloud\Tests\CloudMonitoring;

use PHPUnit_Framework_TestCase;
use OpenCloud\CloudMonitoring\Service;

class AccountTest extends PHPUnit_Framework_TestCase
{
    
    public function __construct()
    {
        $this->service = new Service(
            $this->connection,
            'cloudMonitoring',
            'DFW',
            'publicURL'
        );
    }

    public function testAccountClass()
    {
        echo get_class($this->service->resource('account'));
        $this->expectOutputRegex('#Account#');
    }

    public function testAccountGET()
    {
        $resource = $this->service->resource('account');
        $before = get_object_vars($resource);
        $resource->get();
        foreach ($resource as $var => $val) {
            $this->assertTrue($val != $before[$var]);
        }
    }

    public function testAccountPUT()
    {
        $resource = $this->service->resource('account');
        $before = get_object_vars($resource);
        $resource->Update(array(
            'metadata' => array(
                'test-key' => 'Lorem ipsum'
            )
        ));
        $before = $resource->metadata;
        $resource->get();
        $this->assertEquals((object) $before, (object) $resource->metadata);
    }

}