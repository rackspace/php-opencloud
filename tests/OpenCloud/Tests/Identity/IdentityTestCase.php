<?php

namespace OpenCloud\Tests\Identity;

use OpenCloud\Tests\OpenCloudTestCase;
use OpenCloud\Identity\Service as IdentityService;

class IdentityTestCase extends OpenCloudTestCase
{
    public function setupObjects()
    {
        $this->service = IdentityService::factory($this->getClient());
    }
}