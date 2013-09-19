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

namespace OpenCloud\Tests\Volume;

use PHPUnit_Framework_TestCase;
use OpenCloud\Tests\StubConnection;
use OpenCloud\Volume\Service;
use OpenCloud\Volume\Resource\Snapshot;

class publicSnapshot extends Snapshot
{

    public function CreateJson()
    {
        return parent::createJson();
    }

}

class SnapshotTest extends PHPUnit_Framework_TestCase
{

    private $snap;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $serv = new Service($conn, 'cloudBlockStorage', array('DFW'), 'publicURL');
        $this->snap = new publicSnapshot($serv);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->snap->Update();
    }

    public function testName()
    {
        $this->snap->display_name = 'FOOBAR';
        $this->assertEquals('FOOBAR', $this->snap->Name());
    }

    public function testJsonName()
    {
        $this->assertEquals('snapshot', $this->snap->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('snapshots', $this->snap->ResourceName());
    }

    public function testCreateJson()
    {
        $this->snap->display_name = 'BARFOO';
        $obj = $this->snap->createJson();
        $this->assertEquals('BARFOO', $obj->snapshot->display_name);
    }

}
