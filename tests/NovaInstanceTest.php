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

require_once('stub_conn.inc');
require_once('compute.inc');
require_once('novainstance.inc');

// make a real class from the abstract one
class MyNovaInstance extends OpenCloud\Compute\NovaInstance {
	public
		$status,
		$updated,
		$hostId,
		$addresses,
		$links,
		$image,
		$hostname,
		$flavor,
		$id,
		$user_id,
		$name,
		$created,
		$tenant_id,
		$accessIPv4,
		$accessIPv6,
		$volume,
		$progress,
		$adminPass,
		$metadata;
	public function Refresh($id) { return parent::Refresh($id); }
	public function ResourceName() {
	    try {
	        parent::ResourceName();
	    } catch (OpenCloud\UrlError $e) {
	        return 'instances';
	    }
	}
	public function JsonName() {
	    try {
    	    parent::JsonName();
    	} catch (OpenCloud\DocumentError $e) {
    	    return 'instance';
    	}
	}
}

class NovaInstanceTest extends PHPUnit_Framework_TestCase
{
	private
	    $service,
		$instance;
	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new OpenCloud\Compute(
			$conn,
			'cloudServersOpenStack',
			'DFW',
			'publicURL'
		);
		$this->instance = new MyNovaInstance($this->service);
	}

	/**
	 * Tests
	 */
	public function test__construct() {
	    $inst = new MyNovaInstance($this->service);
	    $this->assertEquals(
	        'MyNovaInstance',
	        get_class($inst));
	}
	/**
	 * @expectedException OpenCloud\AttributeError
	 */
	public function test__set() {
	    $this->instance->FOOBAR = 'BAZ';
	}
	public function testUrl() {
	    $this->instance->id = '12';
	    $this->assertEquals(
	        'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/instances/12',
	        $this->instance->Url());
	}
	public function testRefresh() {
	    $this->instance->Refresh('SERVER-ID');
	    $this->assertEquals('ACTIVE', $this->instance->status);
	}
	public function testWaitFor() {
	    $this->instance->id = '11';
	    $this->instance->WaitFor('FOOBAR', -1,
	        array($this, 'WaitForCallBack'));
	    $this->assertEquals(
	        'FOOBAR',
	        $this->instance->status);
	}
	// this is called by the WaitFor function, above
	public function WaitForCallBack($server) {
	    $server->status = 'FOOBAR';
	}
	/**
	 * @expectedException OpenCloud\CreateError
	 */
	public function testCreate() {
		$this->instance->Create();
	}
	/**
	 * @expectedException OpenCloud\UpdateError
	 */
	public function testUpdate() {
		$this->instance->Update();
	}
	/**
	 * @expectedException OpenCloud\UrlError
	 */
	public function testDelete() {
		$this->instance->Delete();
	}
	public function testName() {
		$this->assertEquals(
			'',
			$this->instance->Name());
	}
}
