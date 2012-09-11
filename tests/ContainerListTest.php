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

require_once('containerlist.inc');
require_once('stub_conn.inc');
require_once('stub_service.inc');

class ContainerListTest extends PHPUnit_Framework_TestCase
{
	private
		$service,
		$container;

	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new StubService(
			$conn,
			'object-store',
			'cloudFiles',
			'DFW',
			'publicURL'
		);
	}

	/**
	 * Tests
	 */
	public function testNext() {
		$obj = new OpenCloud\ObjectStore\DataObject($this->service);
		$list = new OpenCloud\ObjectStore\ContainerList(
			$this->service,
			array($obj));
		$n = $list->Next();
		$this->assertEquals('OpenCloud\ObjectStore\Container', get_class($n));
	}
}
