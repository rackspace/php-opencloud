<?php

/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Tests\Compute\ComputeTestCase;

class KeyPairTest extends ComputeTestCase
{

    public function test_Service_Methods()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\KeyPair',
            $this->service->keypair()
        );
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->listKeypairs()
        );
    }
    
    public function test_Url()
    {
        $keypair = $this->service->keypair(array('name' => 'foo'));
        $this->assertRegExp('#/os-keypairs/foo$#', (string) $keypair->getUrl());
    }
    
    public function test_Create()
    {
        $keypair = $this->service->keypair(array('name' => 'foo'));
        $this->assertNotNull($keypair->create());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Update_Fails()
    {
        $this->service->keypair()->update();
    }
    
    public function test_Upload()
    {
        $path = __DIR__ . '/test.key';
        $contents = file_get_contents($path);
        
        $keypair = $this->service->keypair();
        $keypair->upload(array('path' => $path));
        $this->assertEquals($contents, $keypair->getPublicKey());
        
        $keypair->upload(array('data' => $contents));
        $this->assertEquals($contents, $keypair->getPublicKey());
    }
    
    /**
     * @expectedException OpenCloud\Compute\Exception\KeyPairException
     */
    public function test_Upload_Fails_IncorrectPath()
    {
        $this->service->keypair()->upload(array('path' => 'foo'));
    }
    
    /**
     * @expectedException OpenCloud\Compute\Exception\KeyPairException
     */
    public function test_Upload_Fails_NoKey()
    {
        $this->service->keypair()->upload();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Name_Validity()
    {
        $this->service->keypair()->setName('!!!');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Name_Validity_Create()
    {
        $this->service->keypair()->create(array('name' => '!!!'));
    }
    
}