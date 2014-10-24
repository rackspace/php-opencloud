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

namespace OpenCloud\Tests\Image\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Image\Enum\MemberStatus;
use OpenCloud\Image\Resource\Member;
use OpenCloud\Tests\OpenCloudTestCase;

class MemberTest extends OpenCloudTestCase
{
    public function setupObjects()
    {
        $this->member = new Member($this->getClient()->imageService('cloudImages', 'IAD'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_Update_Status_Fails_With_Incorrect_State()
    {
        $this->member->updateStatus('foo');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ForbiddenOperationException
     */
    public function test_Update_Status_403_Exception()
    {
        $this->addMockSubscriber(new Response(403));

        $this->member->updateStatus(MemberStatus::ACCEPTED);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ResourceNotFoundException
     */
    public function test_Update_Status_404_Exception()
    {
        $this->addMockSubscriber(new Response(404));

        $this->member->updateStatus(MemberStatus::ACCEPTED);
    }

    /**
     * @expectedException \Guzzle\Http\Exception\BadResponseException
     */
    public function test_Update_Status_Other_Http_Exception()
    {
        $this->addMockSubscriber(new Response(500));

        $this->member->updateStatus(MemberStatus::ACCEPTED);
    }

    public function test_Update_Status_Success()
    {
        $this->addMockSubscriber(new Response(201));

        $response = $this->member->updateStatus(MemberStatus::ACCEPTED);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    public function test_Delete()
    {
        $this->addMockSubscriber(new Response(204));

        $response = $this->member->delete();

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }
}
