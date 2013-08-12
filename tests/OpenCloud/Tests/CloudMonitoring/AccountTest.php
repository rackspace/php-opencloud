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
            'DFW',
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

}