<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale;

use PHPUnit_Framework_TestCase;
use OpenCloud\Autoscale\Resource\GroupConfiguration;
use OpenCloud\Autoscale\Service;

class GroupConfigurationTest extends PHPUnit_Framework_TestCase 
{

    const ENDPOINT = 'https://private-f52bc-autoscale.apiary.io/v1.0/tenantId/';
    const GROUP_ID = '{groupId}';
    
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection';
    const CONFIG_CLASS     = 'OpenCloud\Autoscale\Resource\GroupConfiguration';
    const GROUP_CLASS      = 'OpenCloud\Autoscale\Resource\Group';
    
    private $service;
    
    public function __construct()
    {
        $connection = new FakeConnection(
            'http://example.com', 
            'SECRET'
        );

        $this->service = new Service($connection, 'autoscale', 'DFW', 'publicURL', self::ENDPOINT); 
    }
    
    public function testParentFactory()
    {
        $config = $this->service->group()->getGroupConfig();
        
        $this->assertInstanceOf(
            self::CONFIG_CLASS,
            $config
        );
        
        $this->assertInstanceOf(
            self::GROUP_CLASS,
            $config->getParent()
        );
    }
    
    public function testManualInstantiation()
    {
        $config = new GroupConfiguration($this->service);
        $config->setParent($this->service->group());
        
        $this->assertInstanceOf(
            self::CONFIG_CLASS,
            $config
        );
        
        $this->assertInstanceOf(
            self::GROUP_CLASS,
            $config->parent()
        );
    }
    
    public function testConfig()
    {
        $group = $this->service->group(self::GROUP_ID);
        $group->setGroupConfiguration(null);

        $config = $group->getGroupConfig();

        $this->assertEquals(60, $config->cooldown);
        $this->assertEquals('thisisastring', $config->metadata->firstkey);
    }
    
    public function testLaunchConfig()
    {
        $group = $this->service->group(self::GROUP_ID);
        $config1 = $group->getLaunchConfig();
        
        $group->setLaunchConfiguration(null);
        $config = $group->getLaunchConfig();
        
        $this->assertEquals($config1->getType(), $config->getType());
        
        $this->assertEquals('launch_server', $config->getType());
        
        $server = $config->getArgs()->server;
        $this->assertEquals('0d589460-f177-4b0f-81c1-8ab8903ac7d8', $server->imageRef);
        $this->assertEquals(
            'ssh-rsaAAAAB3Nza...LiPk==user@example.net',
            $server->personality[0]->contents
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testGroupConfigCreateFails()
    {
        $this->service->group(self::GROUP_ID)->getGroupConfig()->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testGroupConfigDeleteFails()
    {
        $this->service->group(self::GROUP_ID)->getGroupConfig()->delete();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testLaunchConfigCreateFails()
    {
        $this->service->group(self::GROUP_ID)->getLaunchConfig()->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testLaunchConfigDeleteFails()
    {
        $this->service->group(self::GROUP_ID)->getLaunchConfig()->delete();
    }
    
}