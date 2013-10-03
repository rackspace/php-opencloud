<?php

namespace OpenCloud\Tests\CloudMonitoring;

use PHPUnit_Framework_TestCase;
use OpenCloud\CloudMonitoring\Service;

class AccountTest extends PHPUnit_Framework_TestCase
{
    
    const ACCOUNT_ID = 'someId';
    
    public function __construct()
    {
        $this->connection = new FakeConnection('example.com', 'SECRET');

        $this->service = new Service(
            $this->connection,
            'cloudMonitoring',
            array('DFW'),
            'publicURL'
        );
        
        $this->resource = $this->service->resource('account');
        $this->resource->refresh();
    }

    public function testAccountClass()
    {
        echo get_class($this->service->resource('account'));
        $this->expectOutputRegex('#Account#');
    }

    public function testAccountGET()
    {
        $this->assertEquals('aValue', $this->resource->metadata->key);
        $this->assertEquals('token12345', $this->resource->webhook_token);
    }

    public function testAccountPUT()
    {
        // Update
        $this->resource->update(array(
            'metadata' => array(
                'key' => 'Lorem ipsum'
            )
        ));
        
        // Retrieve back again
        $this->resource->refresh();

        // Test
        $this->assertObjectHasAttribute('key', $this->resource->metadata);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFails()
    {
        $this->resource->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDeleteFails()
    {
        $this->resource->delete();
    }
    
    public function testGettingProperties()
    {
        $val1 = $this->resource->getProperty(array('foo' => 'bar'), 'foo');
        
        $object = new \stdClass;
        $object->foo = 'bar';
        $val2 = $this->resource->getProperty($object, 'foo');
        
        $this->assertEquals($val1, $val2);
        
        $this->assertFalse($this->resource->getProperty($object, 'baz'));
    }
    
}