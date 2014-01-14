<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Compute\Resource\Server;
use OpenCloud\Tests\Compute\ComputeTestCase;

class VolumeAttachmentTest extends ComputeTestCase
{

    private $attachment;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->makeResponse('{"volumeAttachment":{"device":"/dev/xvdb","serverId":"76ddf257-2771-4097-aab8-b07b52110376","id":"4ab50df6-7480-45df-8604-b1ee39fe857c","volumeId":"4ab50df6-7480-45df-8604-b1ee39fe857c"}}'));
        $this->attachment = $this->server->volumeAttachment('4ab50df6-7480-45df-8604-b1ee39fe857c');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->attachment->update();
    }

    public function testName()
    {
        $this->assertEquals('Attachment [4ab50df6-7480-45df-8604-b1ee39fe857c]', $this->attachment->Name());
    }

}
