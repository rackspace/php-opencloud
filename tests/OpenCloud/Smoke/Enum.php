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
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke;

/**
 * Description of Enum
 *
 * @link
 */
class Enum
{
    const USER_AGENT = 'PHP OpenCloud SMOKETEST';
    
    const CREDS_FILENAME = '.smoketestCredentials';
    
    const GLOBAL_PREFIX = 'PHPSmokeTest';
    
    // Environment variable constants
    const ENV_PREFIX   = 'PHP_OpenCloud_';
    const ENV_USERNAME = 'USERNAME';
    const ENV_API_KEY  = 'API_KEY';
    const ENV_PASSWORD = 'PASSWORD';
    const ENV_REGION   = 'REGION';
    const ENV_IDENTITY_ENDPOINT = 'IDENTITY_ENDPOINT';
    
    // Defaults
    const DEFAULT_REGION = 'IAD';
    
    // How many iterations do we want for resource lists? We don't have all day...
    const DISPLAY_ITER_LIMIT = 10;
    
    const DIVIDER = '-------------';
}
