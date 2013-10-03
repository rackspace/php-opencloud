<?php

namespace OpenCloud\Tests\Volume;

use PHPUnit_Framework_TestCase;
use OpenCloud\Tests\StubConnection;
use OpenCloud\Volume\Service;
use OpenCloud\Volume\Resource\VolumeType;

class VolumeTypeTest extends PHPUnit_Framework_TestCase
{

    private $vt;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $serv = new Service(
            $conn, 'cloudBlockStorage', array('DFW'), 'publicURL'
        );
        $this->vt = new VolumeType($serv);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreate()
    {
        $this->vt->Create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->vt->Update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDelete()
    {
        $this->vt->Delete();
    }

    public function testJsonName()
    {
        $this->assertEquals('volume_type', $this->vt->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('types', $this->vt->ResourceName());
    }

}
