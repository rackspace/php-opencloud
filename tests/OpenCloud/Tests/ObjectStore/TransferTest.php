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

use OpenCloud\ObjectStore\Upload\TransferBuilder;

/**
 * Description of TransferTest
 * 
 * @link 
 */
class TransferTest extends \OpenCloud\Tests\MockTestObserver
{
    
    public function __construct()
    {
        $this->service = $this->getClient()->objectStoreService('cloudFiles', 'DFW');  
    }
    
    public function test_Transfer_Builder()
    {
        $transfer = TransferBuilder::newInstance()
            ->setOption('objectName', $options['name'])
            ->setEntityBody(EntityBody::factory($body))
            ->setContainer($this);
    }
    
}