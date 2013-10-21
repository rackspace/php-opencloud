<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

use OpenCloud\Compute\Resource\Server;

class VolumeAttachmentTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $attachment;

    public function __construct()
    {
        $service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW', 'publicURL');
        $server = new Server($service, 'XXX');
        $this->attachment = $server->volumeAttachment('FOO');
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
        $this->assertEquals('Attachment [FOO]', $this->attachment->Name());
    }
    
    public function testCreate()
    {
        $this->attachment->device = 'foo';
        $this->attachment->create();
    }

}
