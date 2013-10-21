<?php

/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */
namespace OpenCloud\Tests\ObjectStore;

/**
 * Description of ServiceTest
 * 
 * @link 
 */
class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    public function __construct()
    {
        $this->service = $this->getClient()->objectStoreService('cloudFiles', 'DFW');
    }
    
    public function test__construct()
    {
        $service = $this->getClient()->objectStoreService('cloudFiles', 'DFW');
        $this->assertInstanceOf('OpenCloud\ObjectStore\Service', $service);
        $this->assertInstanceOf('OpenCloud\ObjectStore\CDNService', $service->getCdnService());
    }
    
    public function test_Url_Secret()
    {
        $this->service->setTempUrlSecret('foo');
        $this->assertEquals('foo', $this->service->getTempUrlSecret());
        
        // Random val
        $this->service->setTempUrlSecret();
        $temp = $this->service->getTempUrlSecret();
        $this->assertTrue($temp && $temp != 'foo');
    }
    
    public function test_List_Containers()
    {
        $list = $this->service->listContainers();
        
        $this->assertInstanceOf('OpenCloud\Common\Collection', $list);
        $this->assertEquals('', $list->first()->getName());
        
        $partialList = $this->service->listContainers(array('limit' => 5));
        $this->assertEqual(5, $partialList->count());
    }
    
    public function test_Create_Container()
    {
        
    }
    
    public function test_Check_ContainerName()
    {
        
    }
    
    public function test_Bulk_Extract()
    {
        
    }
    
    public function test_Bulk_Delete()
    {
        
    }
}