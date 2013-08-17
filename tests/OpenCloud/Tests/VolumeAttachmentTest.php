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

namespace OpenCloud\Tests;

require_once('StubConnection.php');
require_once('StubService.php');

use OpenCloud\Compute\Server;
use OpenCloud\Compute\VolumeAttachment;

class VolumeAttachmentTest extends \PHPUnit_Framework_TestCase
{

    private $attachment;

    public function __construct()
    {
        $connection = new StubConnection('http://example.com', 'SECRET');
        $service = $connection->compute(null, 'DFW');

        $server = new Server($service, 'XXX');
        $this->attachment = $server->VolumeAttachment('FOO');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->attachment->Update();
    }

    public function testName()
    {
        $this->assertEquals('Attachment [FOO]', $this->attachment->Name());
    }

}
