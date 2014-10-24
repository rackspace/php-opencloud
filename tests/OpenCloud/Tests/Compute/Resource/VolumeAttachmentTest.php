<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version   1.0.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

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
