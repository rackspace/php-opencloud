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

require_once('objstorebase.php');
require_once('http.php');

// stub class, since ObjStoreBase is abstract
class MyObjStoreBase extends \OpenCloud\ObjectStore\ObjStoreBase {
	public $name='FOOBAR';
}

class ObjStoreBaseTest extends PHPUnit_Framework_TestCase
{
    private
        $obj;
    public function __construct() {
        $this->obj = new MyObjStoreBase();
    }
    /**
     * Tests
     */
    public function test__construct() {
        $this->obj = new MyObjStoreBase();
        $this->assertEquals(
            'OpenCloud\Metadata',
            get_class($this->obj->metadata));
    }
    /**
     * @expectedException OpenCloud\ObjectStore\MetadataPrefixError
     */
    public function testGetMetadata() {
        $blank = new OpenCloud\Base\Request\Response\Blank;
        $blank->headers = array(
            'X-Meta-Something'=>'FOO',
            'X-Meta-Else'=>'BAR'
        );
        $this->obj->GetMetadata($blank);
    }
    /**
     * @expectedException OpenCloud\ObjectStore\MetadataPrefixError
     */
    public function testMetadataHeaders() {
    	$this->obj->metadata = new \stdClass();
    	$this->obj->metadata->foo = 'BAR';
    	$arr = $this->obj->MetadataHeaders();
    	$this->assertEquals(
    		'',
    		$arr['foo']);
    }
    public function testName() {
    	$this->assertEquals(
    		'FOOBAR',
    		$this->obj->Name());
    }
    public function testJsonName() {
    	$this->assertEquals(
    		NULL,
    		$this->obj->JsonName());
    }
    public function testJsonCollectionName() {
    	$this->assertEquals(
    		NULL,
    		$this->obj->JsonCollectionName());
    }
    public function testJsonCollectionElement() {
    	$this->assertEquals(
    		NULL,
    		$this->obj->JsonCollectionElement());
    }
}
