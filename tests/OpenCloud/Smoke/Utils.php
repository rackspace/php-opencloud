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
 * Description of Utils
 *
 * @link
 */
class Utils
{
    /**
     * Basic logging function.
     *
     * @param string $string
     */
    public static function log($string)
    {
        echo $string . PHP_EOL;
    }
    
    public static function convertArgsToString(array $args)
    {
        $format = $args[0];
        unset($args[0]);
        
        return vsprintf($format, $args);
    }
    
    /**
     * A logging function similar to sprintf(). Accepts a format string as a
     * first argument, and an array as a second argument to stock the format.
     */
    public static function logf()
    {
        $string = self::convertArgsToString(func_get_args());
        return self::log($string);
    }
    
    public static function logd()
    {
        return self::log(PHP_EOL . Enum::DIVIDER);
    }
   
    /**
     * Outputs help.
     */
    public static function help()
    {
        $output = <<<EOF
Switches:

    -D --debug      Turn debug mode ON
    -H --help       Display help message
    -A --all        Show all possible units to run
    -I --include    Include a particular unit
    -E --exclude    Exclude a particular unit

To exclude/include multiple units, either repeat the switch:
            
    php Runner.php -Iautoscale --include="compute"
            
Or pass in a string delimeted with commas:
            
    php Runner.php -Iautoscale,compute,queues

EOF;
        return self::log($output);
    }
        
    public static function getEnvVar($name, $prefix = Enum::ENV_PREFIX)
    {
        if (empty($_ENV)) {
            throw new SmokeException(
                'Your $_ENV superglobals are empty. Please check your php.ini file.'
            );
        }
        return (!isset($_ENV[$prefix . $name])) ? false : $_ENV[$prefix . $name];
    }
    
    public static function getRegion()
    {
        if (false !== ($region = self::getEnvVar(Enum::ENV_REGION))) {
            return $region;
        } else {
            return Enum::DEFAULT_REGION;
        }
    }
    
    public static function getIdentityEndpoint()
    {
        if (false !== ($endpoint = self::getEnvVar(Enum::ENV_IDENTITY_ENDPOINT))) {
            return $endpoint;
        } else {
            return \OpenCloud\Rackspace::US_IDENTITY_ENDPOINT;
        }
    }
}
