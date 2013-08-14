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

namespace OpenCloud\Tests;

use OpenCloud\Common\ObjectStore;

// stub class, since ObjStoreBase is abstract
class MyObjStoreBase extends ObjectStore
{
    public $name = 'FOOBAR';
}

class MyNamelessObjectStore extends ObjectStore
{
}

class ObjStoreBaseTest extends \PHPUnit_Framework_TestCase
{

    private $obj;
    private $nameless;

    public function __construct()
    {
        $this->obj      = new MyObjStoreBase;
        $this->nameless = new MyNamelessObjectStore;
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->obj = new MyObjStoreBase();
        $this->assertEquals(
            'OpenCloud\Common\Metadata', get_class($this->obj->metadata));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\MetadataPrefixError
     */
    public function testGetMetadata()
    {
        $blank = new \OpenCloud\Common\Request\Response\Blank;
        $blank->headers = array(
            'X-Meta-Something' => 'FOO',
            'X-Meta-Else' => 'BAR'
        );
        $this->obj->GetMetadata($blank);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\MetadataPrefixError
     */
    public function testMetadataHeaders()
    {
        $this->obj->metadata = new \stdClass();
        $this->obj->metadata->foo = 'BAR';
        $this->obj->metadata->baz = 'BAR';
        $arr = $this->obj->MetadataHeaders();
        $this->assertEquals('', $arr['foo']);
    }

    public function testName()
    {
        $this->assertEquals('FOOBAR', $this->obj->name());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NameError
     */
    public function testGettingNameFailsIfNotSet()
    {
        $this->nameless->name();
    }

    public function testJsonName()
    {
        $this->assertEquals(
            NULL, $this->obj->JsonName());
    }

    public function testJsonCollectionName()
    {
        $this->assertEquals(
            NULL, $this->obj->JsonCollectionName());
    }

    public function testJsonCollectionElement()
    {
        $this->assertEquals(
            NULL, $this->obj->JsonCollectionElement());
    }

}
