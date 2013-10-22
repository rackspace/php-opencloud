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

use OpenCloud\ObjectStore\Service;
use OpenCloud\ObjectStore\Constants\UrlType;

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
        $account = $this->service->getAccount();
        $account->setTempUrlSecret('foo');
        $this->assertEquals('foo', $account->getTempUrlSecret());
        
        // Random val
        $account->setTempUrlSecret();
        $temp = $account->getTempUrlSecret();
        $this->assertTrue($temp && $temp != 'foo');
    }
    
    public function test_List_Containers()
    {
        $list = $this->service->listContainers();
        
        $this->assertInstanceOf('OpenCloud\Common\Collection', $list);
        $this->assertEquals('a', $list->first()->getName());
        
        $partialList = $this->service->listContainers(array('limit' => 5));
        $this->assertEquals(5, $partialList->count());
    }
    
    public function test_Create_Container()
    {
        $container = $this->service->createContainer('fooBar');
        
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $container);
        $this->assertEquals('fooBar', $container->getName());
        $this->assertEquals('JackWolf', $container->getMetadata()->getProperty('InspectedBy'));
       
        $this->assertFalse($this->service->createContainer('existing-container'));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bad_Container_Name_Empty()
    {
        $this->service->createContainer('');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bad_Container_Name_Slashes()
    {
        $this->service->createContainer('foo/bar');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bad_Container_Name_Long()
    {
        $this->service->createContainer(str_repeat('a', Service::MAX_CONTAINER_NAME_LENGTH + 1));
    }
    
    public function test_Bulk_Extract()
    {
        $response = $this->service->bulkExtract('fooBarContainer', 'CONTENT', UrlType::TAR);
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /**
     * @expectedException OpenCloud\ObjectStore\Exception\BulkOperationException
     */
    public function test_Bad_Bulk_Extract()
    {
        $response = $this->service->bulkExtract('bad-container', 'CONTENT', UrlType::TAR);
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bulk_Extract_With_Incorrect_Type()
    {
        $this->service->bulkExtract('bad-container', 'CONTENT', 'foo');
    }
    
    public function test_Bulk_Delete()
    {
        $response = $this->service->bulkDelete(array('foo/Bar', 'foo/Baz'));
        $this->assertEquals(202, $response->getStatusCode());
    }
    
    /**
     * @expectedException OpenCloud\ObjectStore\Exception\BulkOperationException
     */
    public function test_Bad_Bulk_Delete()
    {
        $this->service->bulkDelete(array('nonEmptyContainer'));
    }
    
    public function test_Accounts()
    {
        $account = $this->service->getAccount();

        $this->assertInstanceOf('OpenCloud\Common\Metadata', $account->getDetails());
        
        $this->assertEquals('50000000', $account->getBytesUsed());
        $this->assertEquals('1000000', $account->getObjectCount());
        $this->assertEquals('20', $account->getContainerCount());
    }
    
}