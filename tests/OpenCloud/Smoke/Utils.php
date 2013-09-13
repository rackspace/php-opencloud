<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
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
     * The current step of the process.
     * 
     * @var int 
     */
    private static $count = 0;
    
    /**
     * Basic logging function.
     * 
     * @param string $string
     */
    public static function log($string)
    {
        echo PHP_EOL . $string . PHP_EOL;
    }
    
    /**
     * A logging function similar to sprintf(). Accepts a format string as a 
     * first argument, and an array as a second argument to stock the format.
     */
    public static function logf()
    {
        $args = func_get_args();
        $format = $args[0];
        unset($args[0]);
        
        $string = vsprintf($format, $args);
        return self::log($string);
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
    
    public static function step()
    {
        $args = func_get_args();
        
        // Override inputted string with count
        $format = 'Step %s: ' . $args[0];
        $args[0] = self::$count++;
        
        $string = vsprintf($format, $args);
        return self::log($string);
    }
    
}