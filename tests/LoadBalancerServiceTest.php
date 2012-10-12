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
require_once('stub_conn.inc');

class LoadBalancerServiceTest extends PHPUnit_Framework_TestCase
{
	private
		$conn, 		// connection
		$service;	// volume service

	public function __construct() {
		$this->conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new OpenCloud\LoadBalancerService(
			$this->conn,
			'cloudLoadBalancers',
			'DFW',
			'publicURL'
		);
	}
	/**
	 * Tests
	 */
	public function test__construct() {
		$this->service = new OpenCloud\LoadBalancerService(
			$this->conn,
			'cloudLoadBalancers',
			'DFW',
			'publicURL'
		);
		$this->assertEquals(
			'OpenCloud\LoadBalancerService',
			get_class($this->service));
	}
	public function testUrl() {
		$this->assertEquals(
'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers',
			$this->service->Url());
	}
	public function testLoadBalancer() {
		$this->assertEquals(
			'OpenCloud\LoadBalancerService\LoadBalancer',
			get_class($this->service->LoadBalancer()));
	}
	public function testLoadBalancerList() {
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($this->service->LoadBalancerList()));
	}
}