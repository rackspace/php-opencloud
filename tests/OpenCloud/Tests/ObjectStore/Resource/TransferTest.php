<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\ObjectStore\Resource;

use Guzzle\Http\EntityBody;
use OpenCloud\ObjectStore\Upload\TransferBuilder;

/**
 * Description of TransferTest
 * 
 * @link 
 */
class TransferTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    
    public function __construct()
    {
        $this->service = $this->getClient()->objectStoreService('cloudFiles', 'DFW');  
        $this->container = $this->service->getContainer('container1');
    }
    
    public function test_Consecutive_Transfer()
    {
        $options = array('objectName' => 'NEW_OBJECT');
        
        $transfer = TransferBuilder::newInstance()
            ->setOptions($options)
            ->setEntityBody(EntityBody::factory(str_repeat('A', 100)))
            ->setContainer($this->container)
            ->build();
        
        $this->assertCount(7, $transfer->getOptions());   
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Consecutive_Transfer_Fails_Without_Object_Name()
    {
        TransferBuilder::newInstance()
            ->setOptions(array('objectName' => false))
            ->setEntityBody(EntityBody::factory(str_repeat('A', 100)))
            ->setContainer($this->container)
            ->build(); 
    }
    
}