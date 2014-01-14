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

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\Service\NovaService;

class MyNova extends NovaService {}

class NovaTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $nova;

    public function setupObjects()
    {
        $this->nova = new MyNova(
            $this->getClient(), 'compute', 'cloudServersOpenStack', 'DFW', 'publicURL'
        );
    }

    /**
     * Tests
     */
    public function testUrl()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/foo',
            (string) $this->nova->getUrl('foo')
        );
    }

    public function testFlavor()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Flavor', $this->nova->flavor());
    }

    public function testFlavorList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->nova->flavorList());
    }

}
