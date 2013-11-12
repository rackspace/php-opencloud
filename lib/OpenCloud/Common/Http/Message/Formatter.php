<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http\Message;

use OpenCloud\Common\Constants\Mime;

class Formatter
{

    public static function decode(Response $response)
    {
        if ($response->getHeader('Content-Type') == Mime::JSON) {
            return json_decode((string) $response->getBody());
        }
    }

    public static function encode($body)
    {
        return json_encode($body);
    }

}