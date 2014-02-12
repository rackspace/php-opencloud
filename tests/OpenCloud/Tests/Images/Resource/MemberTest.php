<?php

namespace OpenCloud\Tests\Images\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Images\Enum\MemberStatus;
use OpenCloud\Images\Resource\Member;
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