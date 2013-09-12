<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Metadata;
use OpenCloud\Tests\StubConnection;

class MetadataTest extends PHPUnit_Framework_TestCase
{

    private $metadata;

    public function __construct()
    {
        $this->metadata = new Metadata;
    }

    public function test__set()
    {
        $this->metadata->foo = 'bar';
        $this->assertEquals('bar', $this->metadata->foo);
    }

    public function testKeylist()
    {
        $this->metadata->foo = 'bar';
        $this->assertTrue(in_array('foo', $this->metadata->Keylist()));
    }

    public function testSetArray()
    {
        $this->metadata->setArray(array('opt' => 'uno', 'foobar' => 'baz'));
        $this->assertEquals('uno', $this->metadata->opt);
        $this->metadata->setArray(array('X-one' => 1, 'X-two' => 2), 'X-');
        $this->assertEquals(2, $this->metadata->two);
    }

}
