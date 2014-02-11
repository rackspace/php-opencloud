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

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\Service\NovaService;

class MyNova extends NovaService
{
}

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
            (string)$this->nova->getUrl('foo')
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
