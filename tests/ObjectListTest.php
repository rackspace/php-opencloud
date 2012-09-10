<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('dataobject.inc');
require_once('objectlist.inc');
require_once('container.inc');
require_once('stub_conn.inc');
require_once('stub_service.inc');

class ObjectListTest extends PHPUnit_Framework_TestCase
{
	private
		$container;

	public function __construct() {
	    $this->container = new OpenCloud\ObjectStore\Container(
	        new StubService(
                new StubConnection(
                    'http://example.com',
                    'SECRET'
                ),
                'object-store',
                'cloudFiles',
                'DFW',
                'publicURL'
	        )
	    );
	    $this->container->name = 'Test Container';
	}

	/**
	 * Tests
	 */
	public function testNext() {
	    $obj = new OpenCloud\ObjectStore\DataObject($this->container);
	    $obj->name = 'Test Object';
	    $list = new OpenCloud\ObjectStore\ObjectList(
	        $this->container,
	        array($obj));
	    $this->assertEquals(
	        'OpenCloud\ObjectStore\DataObject', get_class($list->Next()));
	    $this->assertEquals(
	        FALSE,
	        $list->Next());
	}
}
