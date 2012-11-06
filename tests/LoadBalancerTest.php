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
	/*
	public static 
		$json_name = 'loadBalancer',
		$url_resource = 'loadbalancers';
	*/
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
	/**
	 * @expectedException OpenCloud\DomainError
	 */
	public function testAddNode() {
		$this->lb->AddNode('1.1.1.1', 80);
		$this->assertEquals(
			'1.1.1.1',
			$this->lb->nodes[0]->address);
		// this should trigger an error
		$this->lb->AddNode('1.1.1.2', 80, 'foobar');
	}
	public function testAddVirtualIp() {
		$this->lb->AddVirtualIp('public');
		$this->assertEquals(
			'PUBLIC',
			$this->lb->virtualIps[0]->type);
		// trigger error
		$this->lb->AddVirtualIp('foobar');
	}
	public function testStats() {
		$this->lb->id = 1024;
		$x = $this->lb->Stats();
		$this->assertEquals(
			'stdClass',
			get_class($x));
		$this->assertEquals(
			10,
			$x->connectTimeOut);
	}
	public function testCreateJson() {
		$this->lb->name = 'FOOBAR';
		$obj = $this->lb->CreateJson();
		$this->assertEquals(
			'FOOBAR',
			$obj->loadBalancer->name);
	}
}
