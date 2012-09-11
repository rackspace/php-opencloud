<?php
/**
 * Unit Tests
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

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
