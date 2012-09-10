<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('stub_conn.inc');
require_once('stub_service.inc');
require_once('dbservice.inc');

class InstanceTest extends PHPUnit_Framework_TestCase
{
	private
		$service,
		$instance;
	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new OpenCloud\DbService(
			$conn, 'cloudDatabases', 'DFW', 'publicURL');
		$this->instance = new OpenCloud\DbService\Instance(
			$this->service,'INSTANCE-ID');
	}
	/**
	 * Tests
	 */
	public function test___construct() {
		$this->assertEquals(
			'OpenCloud\DbService\Instance',
			get_class($this->instance));
	}
	public function testCreate() {
		$resp = $this->instance->Create();
		$this->assertEquals(
			'OpenCloud\BlankResponse',
			get_class($resp));
		$this->assertEquals(
			'56a0c515-9999-4ef1-9fe2-76be46a3aaaa',
			$this->instance->id);
	}
	/**
	 * @expectedException OpenCloud\DbService\InstanceUpdateError
	 */
	public function testUpdate() {
		$this->instance->Update();
	}
	public function testDelete() {
		$response = $this->instance->Delete();
		$this->assertEquals(202, $response->HttpStatus());
	}
	public function testRestart() {
		$this->assertEquals(
			200,
			$this->instance->Restart()->HttpStatus());
	}
	public function testResize() {
		$flavor = $this->service->Flavor(2);
		$this->assertEquals(
			200,
			$this->instance->Resize($flavor)->HttpStatus());
	}
	public function testResizeVolume() {
		$this->assertEquals(
			200,
			$this->instance->ResizeVolume(4)->HttpStatus());
	}
	public function testEnableRootUser() {
		$this->assertEquals(
			'OpenCloud\DbService\User',
			get_class($this->instance->EnableRootUser()));
	}
	public function testIsRootEnabled() {
		$this->assertEquals(
			FALSE,
			$this->instance->IsRootEnabled());
	}
	public function testDatabase() {
	    $this->assertEquals(
	        'OpenCloud\DbService\Database',
	        get_class($this->instance->Database('FOO')));
	}
	public function testUser() {
	    $this->assertEquals(
	        'OpenCloud\DbService\User',
	        get_class($this->instance->User('BAR',array('FOO'))));
	}
	public function testDatabaseList() {
		$this->assertEquals(
			'OpenCloud\DbService\DatabaseList',
			get_class($this->instance->DatabaseList()));
	}
	public function testUserList() {
		$this->assertEquals(
			'OpenCloud\DbService\UserList',
			get_class($this->instance->UserList()));
	}
}
