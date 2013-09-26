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

namespace OpenCloud\Tests\ObjectStore;

use OpenCloud\ObjectStore\Resource\AbstractStorageObject;

// stub class, since ObjStoreBase is abstract
class MyObjStoreBase extends AbstractStorageObject
{
    public $name = 'FOOBAR';
}

class MyNamelessObjectStore extends AbstractStorageObject {}

class ObjStoreBaseTest extends \OpenCloud\Tests\OpenCloudTestCase
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
        $this->assertInstanceOf('OpenCloud\Common\Metadata', $this->obj->metadata);
    }


    /**
     * @expectedException \OpenCloud\Common\Exceptions\MetadataPrefixError
     */
    public function testMetadataHeaders()
    {
        $this->obj->metadata = new \stdClass();
        $this->obj->metadata->foo = 'BAR';
        $this->obj->metadata->baz = 'BAR';
        $arr = $this->obj->metadataHeaders();
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
        $this->assertNull($this->obj->JsonName());
    }

    public function testJsonCollectionName()
    {
        $this->assertNull($this->obj->JsonCollectionName());
    }

    public function testJsonCollectionElement()
    {
        $this->assertNull($this->obj->JsonCollectionElement());
    }

}
