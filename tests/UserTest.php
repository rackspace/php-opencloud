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

require_once('stub_conn.inc');
require_once('user.inc');
require_once('dbservice.inc');

class UserTest extends PHPUnit_Framework_TestCase
{
	private
		$inst,
		$user;
	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$useraas = new OpenCloud\DbService(
		    $conn, 'cloudDatabases', 'DFW', 'publicURL');
		$this->inst = new OpenCloud\DbService\Instance($useraas);
		$this->inst->id = '12345678';
		$this->user = new OpenCloud\DbService\User($this->inst);
	}
	/**
	 * Tests
	 */
	public function test__construct() {
		$this->assertEquals(
			'OpenCloud\DbService\User',
			get_class(new OpenCloud\DbService\User($this->inst)));
		$u = new OpenCloud\DbService\User(
		    $this->inst,
		    'glen',
		    array('one','two'));
		$this->assertEquals('glen', $u->name);
		$this->assertEquals(2, count($u->databases));
	}
	public function testUrl() {
		$this->user->name = 'TEST';
		$this->assertEquals(
			'https://dfw.databases.api.rackspacecloud.com/v1.0/'.
			    'TENANT-ID/instances/12345678/users/TEST',
			$this->user->Url());
	}
	public function testInstance() {
		$this->assertEquals(
			'OpenCloud\DbService\Instance',
			get_class($this->user->Instance()));
	}
	public function testService() {
		$this->assertEquals(
			'OpenCloud\DbService',
			get_class($this->user->Service()));
	}
	public function testAddDatabase() {
		$this->user->AddDatabase('FOO');
		$this->assertEquals(
			TRUE,
			in_array('FOO', $this->user->databases));
	}
	public function testCreate() {
		$response = $this->user->Create(array(
			'name' => 'FOOBAR',
			'password' => 'BAZ'));
		$this->assertLessThan(
			205,
			$response->HttpStatus());
		$this->assertEquals(
			'FOOBAR',
			$this->user->name);
		$this->assertEquals(
			'BAZ',
			$this->user->password);
	}
	/**
	 * @expectedException OpenCloud\DbService\UserUpdateError
	 */
	public function testUpdate() {
		$this->user->Update();
	}
	public function testDelete() {
		$this->user->name = 'GLEN';
		$response = $this->user->Delete();
		$this->assertLessThan(
			205,
			$response->HttpStatus());
	}
}
