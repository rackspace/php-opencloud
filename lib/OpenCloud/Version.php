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

namespace OpenCloud;

use Guzzle\Common\Version as GuzzleVersion;
use Guzzle\Http\Curl\CurlVersion;

/**
 * Class Version
 *
 * @package OpenCloud
 */
class Version
{
    const VERSION = '1.10.0';

    /**
     * @return string Indicate current SDK version.
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * @return bool|float|string Indicate cURL's version.
     */
    public static function getCurlVersion()
    {
        return CurlVersion::getInstance()->get('version');
    }

    /**
     * @return string Indicate Guzzle's version.
     */
    public static function getGuzzleVersion()
    {
        return GuzzleVersion::VERSION;
    }
}
