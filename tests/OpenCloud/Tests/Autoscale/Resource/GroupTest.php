<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale\Resource;

use OpenCloud\Tests\Autoscale\AutoscaleTestCase;

class GroupTest extends AutoscaleTestCase
{
    /**
     * @mockFile Group_Create
     */
    public function test_Create()
    {
        $blueprint = (object) array(
            'launchConfiguration' => (object) array(
                'args' => (object) array(
                    'loadBalancers' => array(
                        (object) array('port' => 8080, 'loadBalancerId' => 9099),
                    ),
                    'server' => (object) array(
                        'name' => 'autoscale_server',
                        'imageRef' => '0d589460-f177-4b0f-81c1-8ab8903ac7d8',
                        'flavorRef' => '2',
                        'OS-DCF:diskConfig' => 'AUTO',
                        'metadata' => (object) array(
                            'build_config' => 'core',
                            'meta_key_1' => 'meta_value_1',
                            'meta_key_2' => 'meta_value_2',
                        ),
                        'networks' => array(
                            (object) array('uuid' => '11111111-1111-1111-1111-111111111111'),
                            (object) array('uuid' => '00000000-0000-0000-0000-000000000000'),
                        ),
                        'personality' => array(
                            (object) array(
                                'path' => '/root/.csivh',
                                'contents' => 'VGhpcyBpcyBhIHRlc3QgZmlsZS4=',
                            ),
                        ),
                    ),
                    'type' => 'launch_server'
                )
            ),
            'groupConfiguration' => (object) array(
                'maxEntities' => 10,
                'cooldown' => 360,
                'name' => 'testscalinggroup198547',
                'minEntities' => 0,
                'metadata' => (object) array(
                    'gc_meta_key_2' => 'gc_meta_value_2',
                    'gc_meta_key_1' => 'gc_meta_value_1'
                ),
            ),
            'scalingPolicies' => array(
                (object) array(
                    'cooldown' => 0,
                    'type' => 'webhook',
                    'name' => 'scale up by 1',
                    'change' => 1,
                )
            )
        );

        $group = $this->service->group();

        $response = $group->create($blueprint);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(self::GROUP_ID, $group->getId());
    }

    public function test_Group()
    {
        $this->assertInstanceOf('OpenCloud\Autoscale\Resource\Group', $this->group);
        $this->assertEquals($this->group->getId(), self::GROUP_ID);
    }

    /**
     * @mockFile Group_List
     */
    public function testGroups()
    {
        $first = $this->service->groupList()->first();
        $this->assertEquals('389391ef-9961-40a6-8007-906145999283', $first->getId());
    }

    /**
     * @mockFile Group_State
     */
    public function testGroupState()
    {
        $this->assertEquals(10, $this->group->getState()->activeCapacity);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateAlwaysFails()
    {
        $this->group->update();
    }

}