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

require_once('lbservice.inc');
require_once('loadbalancer.inc');
require_once('stub_conn.inc');

class publicLoadBalancer extends OpenCloud\LoadBalancerService\LoadBalancer {
    public function JsonName() { return parent::JsonName(); }
    public function ResourceName() { return parent::ResourceName(); }
    public function CreateJson() { return parent::CreateJson(); }
}

class LoadBalancerTest extends PHPUnit_Framework_TestCase
{
	private
		$conn, 		// connection
		$service,	// service
		$lb;		// load balancer
		
	public function __construct() {
		$this->conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new OpenCloud\LoadBalancerService(
			$this->conn,
			'cloudLoadBalancers',
			'DFW',
			'publicURL'
		);
		$this->lb = new publicLoadBalancer($this->service);
	}
	
	/**
	 * Tests
	 */
	public function testAddNode() {
		$this->lb->AddNode('1.1.1.1', 80);
		$this->assertEquals(
			'1.1.1.1',
			$this->lb->nodes[0]->address);
	}
	public function testAddVirtualIp() {
		$this->lb->AddVirtualIp('public');
		$this->assertEquals(
			'public',
			$this->lb->virtualIps[0]->type);
	}
	public function testCreateJson() {
		$this->lb->name = 'FOOBAR';
		$obj = $this->lb->CreateJson();
		$this->assertEquals(
			'FOOBAR',
			$obj->loadBalancer->name);
	}
	public function testJsonName() {
		$this->assertEquals(
			'loadBalancer',
			$this->lb->JsonName());
	}
	public function testResourceName() {
		$this->assertEquals(
			'loadbalancers',
			$this->lb->ResourceName());
	}
}