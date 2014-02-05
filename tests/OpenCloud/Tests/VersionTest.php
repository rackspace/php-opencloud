<?php

namespace OpenCloud\Tests;

use OpenCloud\Version;

class VersionTest extends OpenCloudTestCase
{
    public function test_Version()
    {
        $this->assertEquals(Version::getVersion(), Version::VERSION);
    }

    public function test_Curl_Version()
    {
        $curl = curl_version();
        $this->assertEquals(Version::getCurlVersion(), $curl['version']);
    }

    public function test_Guzzle_Version()
    {
        $this->assertNotNull(Version::getGuzzleVersion());
    }
} 