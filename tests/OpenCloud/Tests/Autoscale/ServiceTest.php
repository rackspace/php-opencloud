<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale;

use OpenCloud\Tests\OpenCloudTestCase;

class ServiceTest extends OpenCloudTestCase 
{
    public function setupObjects()
    {
        $this->service = $this->getClient()->autoscaleService('autoscale');
    }

    public function testResources()
    {
        $this->assertNotEmpty($this->service->getResources());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UnrecognizedServiceError
     */
    public function testFindingResourceNamespaceFailsIfNotExists()
    {
        $this->service->resource('FooBar');
    }
    
}