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

require_once('lbservice.php');
require_once('loadbalancer.php');
require_once('stub_conn.php');

class publicLoadBalancer extends OpenCloud\LoadBalancerService\LoadBalancer {
	/*
	public static
		$json_name = 'loadBalancer',
		$url_resource = 'loadbalancers';
	*/
    public function CreateJson() { return parent::CreateJson(); }
}
class MySubResource extends OpenCloud\LoadBalancerService\SubResource {
	public static 
		$json_name = 'ignore',
		$url_resource = 'ignore';
	protected
		$_create_keys = array('id');
	public function CreateJson() { return parent::CreateJson(); }
	public function UpdateJson($params=array()) {
		return parent::UpdateJson($params);
	}
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
		$lb = $this->service->LoadBalancer();
		$lb->AddNode('1.1.1.1', 80);
		$this->assertEquals(
			'1.1.1.1',
			$lb->nodes[0]->address);
		// this should trigger an error
		$lb->AddNode('1.1.1.2', 80, 'foobar');
	}
	public function testAddNodes() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$lb->AddNode('1.1.1.1', 80);
		$lb->AddNodes();
	}
	/**
	 * @ expectedException OpenCloud\DomainError
	 */
	public function testAddVirtualIp() {
		$lb = $this->service->LoadBalancer();
		$lb->AddVirtualIp('public');
		$this->assertEquals(
			'PUBLIC',
			$lb->virtualIps[0]->type);
		// trigger error
		//$lb->AddVirtualIp('foobar');
	}
	public function testNode() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/nodes/321',
			$lb->Node('321')->Url()
		);
		$this->assertEquals(
			'OpenCloud\LoadBalancerService\LoadBalancer',
			get_class($lb->Node('345')->Parent()));
		$this->assertEquals(
			'OpenCloud\LoadBalancerService\Node[456]',
			$lb->Node('456')->Name());
		$this->assertEquals(
			'OpenCloud\LoadBalancerService\Metadata',
			get_class($lb->Node('456')->Metadata()));
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($lb->Node('456')->MetadataList()));
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/'.
			  'TENANT-ID/loadbalancers/123/nodes/456',
			$lb->Node('456')->Url());
	}
	public function testNodeList() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($lb->NodeList()));
	}
	public function testNodeEvent() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/nodes/events',
			$lb->NodeEvent()->Url()
		);
	}
	public function testNodeEventList() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($lb->NodeEventList()));
	}
	public function testVirtualIp() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/virtualips',
			$lb->VirtualIp()->Url()
		);
	}
	public function testVirtualIpList() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($lb->VirtualIpList()));
	}
	public function testSessionPersistence() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/sessionpersistence',
			$lb->SessionPersistence()->Url()
		);
	}
	public function testErrorPage() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/errorpage',
			$lb->ErrorPage()->Url()
		);
	}
	public function testStats() {
		$this->lb->id = 1024;
		$x = $this->lb->Stats();
		$this->assertEquals(
			'OpenCloud\LoadBalancerService\Stats',
			get_class($x));
		$this->assertEquals(
			10,
			$x->connectTimeOut);
	}
	public function testUsage() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/usage',
			$lb->Usage()->Url()
		);
	}
	public function testAccess() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/accesslist',
			$lb->Access()->Url()
		);
	}
	public function testAccessList() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($lb->AccessList()));
	}
	public function testConnectionThrottle() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/connectionthrottle',
			$lb->ConnectionThrottle()->Url()
		);
	}
	public function testConnectionLogging() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/connectionlogging',
			$lb->ConnectionLogging()->Url()
		);
	}
	public function testContentCaching() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/contentcaching',
			$lb->ContentCaching()->Url()
		);
	}
	public function testSSLTermination() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/ssltermination',
			$lb->SSLTermination()->Url()
		);
	}
	public function testMetadata() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/'.
				'loadbalancers/123/metadata',
			$lb->Metadata()->Url()
		);
	}
	public function testMetadataList() {
		$lb = $this->service->LoadBalancer();
		$lb->Create();
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($lb->MetadataList()));
	}
	public function testCreateJson() {
		$this->lb->name = 'FOOBAR';
		$obj = $this->lb->CreateJson();
		$this->assertEquals(
			'FOOBAR',
			$obj->loadBalancer->name);
	}
	public function testSubResource() {
		$this->lb->id = '42';
		$sub = new MySubResource($this->lb, '42');
		$this->assertEquals(
			'MySubResource',
			get_class($sub));
		$this->assertEquals(
			'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/'.
			'TENANT-ID/loadbalancers/42/ignore',
			$sub->Url('foo', array('one'=>1)));
		$obj = $sub->UpdateJson();
		$json = json_encode($obj);
		$this->assertEquals(
			'{"ignore":{"id":"42"}}',
			$json);
		$this->assertEquals(
			$this->lb,
			$sub->Parent());
		$this->assertEquals(
			'MySubResource-42',
			$sub->Name());
	}
}
