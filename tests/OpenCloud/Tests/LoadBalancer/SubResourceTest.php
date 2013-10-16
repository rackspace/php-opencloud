<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\LoadBalancer;

class SubResourceTest extends \OpenCloud\Tests\OpenCloudTestCase 
{

    private $service;
    private $loadBalancer;

    public function __construct()
    {
        $this->service = $this->getClient()->loadBalancerService('cloudLoadBalancers', 'DFW', 'publicURL');
        $this->loadBalancer = $this->service->loadBalancer('2000');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testAccessUpdateFails()
    {
        $this->loadBalancer->access()->update();
    }
    
    public function testConnectionCreateGoesToUpdate()
    {
        $this->loadBalancer->connectionLogging()->create();
        $this->loadBalancer->connectionThrottle()->create();
        $this->loadBalancer->contentCaching()->create();
        $this->loadBalancer->errorPage()->create();
        $this->loadBalancer->healthMonitor()->create();
        $this->loadBalancer->SSLTermination()->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testConnectionDeleteFails()
    {
        $this->loadBalancer->connectionLogging()->delete();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testContentCachingDeleteFails()
    {
        $this->loadBalancer->contentCaching()->delete();
    }
    
    public function testMetadata()
    {
        $metadata = $this->loadBalancer->metadata();
        $metadata->key = 'foo';
        $this->assertEquals('foo', $metadata->name());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testReadOnlyCreateFails()
    {
        $this->loadBalancer->nodeEvent()->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testReadOnlyUpdateFails()
    {
        $this->loadBalancer->nodeEvent()->update();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testReadOnlyDeleteFails()
    {
        $this->loadBalancer->nodeEvent()->delete();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testVirtualIPUpdateFails()
    {
        $this->loadBalancer->virtualIp()->update();
    }
    
}
