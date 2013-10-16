<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

class KeyPairTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $compute;
    
    public function __construct()
    {
        $this->compute = $this->getClient()->compute('cloudServersOpenStack', 'DFW', 'publicURL');
    }
    
    public function test_Service_Methods()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\KeyPair',
            $this->compute->keypair()
        );
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection',
            $this->compute->listKeypairs()
        );
    }
    
    public function test_Url()
    {
        $keypair = $this->compute->keypair(array('name' => 'foo'));
        $this->assertRegExp('#/os-keypairs/foo$#', (string) $keypair->getUrl());
    }
    
    public function test_Create()
    {
        $keypair = $this->compute->keypair(array('name' => 'foo'));
        $keypair->create();
        $this->assertEquals(
            '35:9d:d0:c3:4a:80:d3:d8:86:f1:ca:f7:df:c4:f9:d8', 
            $keypair->getFingerprint()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Update_Fails()
    {
        $this->compute->keypair()->update();
    }
    
    public function test_Upload()
    {
        $path = __DIR__ . '/Resource/test.key';
        $contents = file_get_contents($path);
        
        $keypair = $this->compute->keypair();
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
        $this->compute->keypair()->upload(array('path' => 'foo'));
    }
    
    /**
     * @expectedException OpenCloud\Compute\Exception\KeyPairException
     */
    public function test_Upload_Fails_NoKey()
    {
        $this->compute->keypair()->upload();
    }
    
}