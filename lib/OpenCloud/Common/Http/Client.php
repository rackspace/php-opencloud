<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Curl\CurlVersion;

/**
 * Description of Client
 * 
 * @link 
 */ 
class Client extends GuzzleClient
{
    
    const VERSION = '1.7.0';
    
    public function getDefaultUserAgent()
    {
        return 'OpenCloud/' . self::VERSION
            . ' curl/' . CurlVersion::getInstance()->get('version')
            . ' PHP/' . PHP_VERSION;
    }
    
}