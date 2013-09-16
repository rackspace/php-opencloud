<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\OpenStack;
use OpenCloud\Rackspace;
use OpenCloud\Common\Service;
use OpenCloud\Smoke\Enum;
use OpenCloud\Smoke\SmokeException;
use OpenCloud\Smoke\Utils;

/**
 * Description of AbstractUnit
 * 
 * @link 
 */
abstract class AbstractUnit
{
    private static $currentStep = 1;
    
    private static $steps = array();
    
    /**
     * The credentials cache filename.
     * 
     * @var string 
     */
    private $credentialsCacheFile;
    
    /**
     * The connection object which everything routes through.
     * 
     * @var OpenCloud\OpenStack
     */
    protected $connection;
    
    /**
     * The particular service that each unit uses.
     * 
     * @var OpenCloud\Common\Service 
     */
    protected $service;
    
    /**
     * Factory method for instantiating the unit object, and executing its 
     * main algorithm.
     * 
     * @return UnitInterface
     */
    public static function factory()
    {
        $unit = new static();
        
        // Authenticate and establish client
        $unit->initAuth();
        
        // Unit-specific implementations
        $unit->setService($unit->setupService());
        
        // Run execution...
        $unit->main();
        
        // Clean stuff up if necessary...
        $unit->teardown();
        
        return $unit;
    }
    
    public function setConnection(OpenStack $connection)
    {
        $this->connection = $connection;
        return $this;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }
    
    public function getService()
    {
        return $this->service;
    }
    
    protected function initAuth()
    {
        Utils::step('Authenticate'); 
        
        $secret = array(
            'username' => Utils::getEnvVar(Enum::ENV_USERNAME), 
            'apiKey'   => Utils::getEnvVar(Enum::ENV_API_KEY)
        );

        $identityEndpoint = Utils::getIdentityEndpoint();
        
        // Do connection stuff
        $connection = new Rackspace($identityEndpoint, $secret);
        $connection->appendUserAgent(Enum::USER_AGENT);
        $this->setConnection($connection);
        
        // See if we can retrieve credentials
        $this->handleCredentials();
        
        Utils::logf('Using identity endpoint: %s', $identityEndpoint);
        Utils::logf('Using region: %s', Utils::getRegion());
    }
    
    public function handleCredentials()
    {
        $credentialsCacheFile = __DIR__ . '/../Resource/' . Enum::CREDS_FILENAME;
        
        try {
            $fp = fopen($credentialsCacheFile, 'r');
        } catch (Exception $e) {}
        
        // Does the credentials file already exist?
        if (!$fp || !($size = filesize($credentialsCacheFile))) {
            
            // If not, can we create a new one?            
            if (!is_writable($credentialsCacheFile)
                || false === ($fp = fopen($credentialsCacheFile, 'w'))
            ) {
                throw new SmokeException(sprintf(
                    'Credentials file [%s] cannot be written to',
                    $credentialsCacheFile
                ));
            }
            
            Utils::logf('Saving credentials to %s', $credentialsCacheFile);
 
            // Save credentials
            fwrite($fp, serialize($this->getConnection()->exportCredentials()));
            
        } else { 
            
            Utils::logf('Loading credentials from %s', $credentialsCacheFile);
            
            // Read from file
            $string = fread($fp, $size);
            $this->getConnection()->importCredentials(unserialize($string)); 
        }
        
        fclose($fp);
    }
    
    public function getWaiterCallback()
    {
        return function($object) {
            if (!empty($object->error)) {
                var_dump($object->error); 
                die;
            } else {
                Utils::logf(
                    "...Waiting on %s/%-12s %4s\n",
                    $object->name(),
                    $object->status(),
                    isset($object->progress) ? $object->progress . '%' : 0
                );
            }
        };
    }
    
    public function shouldDelete($string)
    {
        return stristr($string, Enum::GLOBAL_PREFIX) !== false;
    }
    
    public function prepend($string)
    {
        return Enum::GLOBAL_PREFIX . $string;
    }
    
    public static function step()
    {
        $args = func_get_args();
        
        // Override inputted string with count
        $format = 'Step %s: ' . $args[0];
        $args[0] = self::$count++;
        $string = vsprintf($format, $args);
        
        // Set array if not set
        if (!isset(static::$steps)) {
            static::$steps = array();
        }
        
        // Increment
        static::$currentStep++;
        
        // Append message to array
        static::$steps[self::$currentStep]['head'] = $string;
        
        // Sort out correct message
        $message = sprintf("Step %n", end());
        
        if (isset(static::$totalSteps)) {
            $message .= sprintf('/%n', static::$totalSteps);
        }
        
        $message .= sprintf(': %s', $string);
        
        // Output
        self::log($message);
    }
    
    public function subStep()
    {
        $args = func_get_args();

        if (isset(static::$steps[self::$currentStep])) {
            
            // Override inputted string with count
            $format = 'Step %s: ' . $args[0];
            $args[0] = self::$count++;
            $string = vsprintf($format, $args);
            
            // If subSteps is not an array, create it
            if (empty(static::$steps[self::$currentStep]['subSteps'])) {
                static::$steps[self::$currentStep]['subSteps'] = array();
            }
            
            // Construct output string
            end(static::$steps[self::$currentStep]['subSteps']);
            $key = key(static::$steps[self::$currentStep]['subSteps']);
            $outputString = sprintf('   %n: %s', $key++, $string);
            
            // Append to array record
            static::$steps[self::$currentStep]['subSteps'][$key] = $string;
            
            // Output
            self::log($outputString);
            
        } else {
            return self::step($args);
        }
    }
    
}