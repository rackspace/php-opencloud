<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud;

/**
 * Class Version
 *
 * @package OpenCloud
 */
class Version 
{

    const VERSION = '1.9.1';

    /**
     * @return string Indicate current SDK version.
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

} 