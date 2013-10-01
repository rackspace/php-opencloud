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

use OpenCloud\Rackspace;

/**
 * The runner runs the smoke test, he's the boss. You determine which units should
 * be run in the command line.
 */
class Runner
{
    /**
     * These are the individual tests, or units, that can execute.
     * 
     * @var array 
     */
    private $units = array(
        'Autoscale',
        'Compute',
        'CloudMonitoring',
        'DNS',
        'Database',
        'LoadBalancer',
        'ObjectStore',
        'Orchestration',
        'Queues',
        'Volume'
    );
    
    public static function run()
    {
        return new static();
    }
    
    public function __construct()
    {
        Utils::log('Welcome to the PHP OpenCloud SmokeTest!' . PHP_EOL);
        
        $start = microtime(true);
        
        $this->handleArguments();
        $this->executeTemplate();
        
        $duration = microtime(true) - $start;
        Utils::logd();
        Utils::logf('Finished all tests! Time taken: %s', $this->formatDuration($duration));
    }
    
    private function formatDuration($duration)
    {
        $string = '';
        
        if (($minutes = floor($duration / 60)) > 0) {
            $string .= "$minutes minute" . (($minutes > 1) ? 's' : '') . ' ';
        }

        if (($seconds = $duration % 60) > 0) {
            if ($minutes > 0) {
                $string .= "and ";
            }
            $string .= "$seconds seconds.";
        }
        
        return $string;
    }
    
    private function handleArguments()
    {        
        $options = getopt('D::H::E::I::A', array(
            'debug::',
            'help::',
            'exclude::',
            'include::',
            'all::'
        ));
        
        foreach ($options as $option => $value) {
            switch ($option) {
                case 'D':
                case 'debug':
                    Utils::log('Debug mode enabled');
                    define(RAXSDK_DEBUG, true);
                    break;
                case 'H':
                case 'help':
                    Utils::help();
                    exit;
                case 'E':
                case 'exclude':
                    $this->insertSpecification($value);
                    break;
                case 'I':
                case 'include':
                    $this->insertSpecification($value, false);
                    break;
                case 'A':
                case 'all':
                    Utils::logf('Possible units are: %s', implode(',', $this->units));
                    break;
            }
        }
        
        $this->validateUnits();
    }
    
    public function insertSpecification($key, $exclude = true)
    {
        $match = false;
        
        $keys = (strpos($key, ',') !== false) ? explode(',', $key) : array($key);
        $keysLower = array_map('strtolower', $keys);
        
        foreach ($this->units as $unit) {
            if (in_array(strtolower($unit), $keysLower)) {
                if (true === $exclude) {
                    $this->excluded[] = $unit;
                } else {
                    $this->included[] = $unit;
                }
                $match = true;
            }
        }
 
        if ($match !== true) {
            throw new SmokeException(sprintf(
                'You cannot "%s" %s because it is not a defined test. Run the '
                    . '-a or --all option to see all available units.',
                ($exclude === true) ? 'exclude' : 'include',
                $key
            ));
        }
    }
    
    private function validateUnits()
    {
        // If no inclusions are explicitly set, run all of them
        if (empty($this->included)) {
            $this->included = $this->units;
        }
        
        // Remove all units from `included` list that are deemed exclusions
        if (!empty($this->excluded)) {
            $this->included = array_diff($this->included, $this->excluded);
        }
    }
    
    public function executeTemplate()
    {
        $connection = $this->createConnection();
        
        foreach ($this->included as $unit) {
            
            $class = __NAMESPACE__ . '\\Unit\\' . $unit;
            
            if (!class_exists($class)) {
                throw new SmokeException(sprintf(
                    '%s class does not exist', $class
                ));
            }
            
            if (!method_exists($class, 'factory')) {
                throw new SmokeException(sprintf(
                    'Factory method does not exist in %s', $class
                ));
            }
            
            Utils::logf(PHP_EOL . 'Executing %s', $class);
            
            $class::factory($connection, $this->included);
        }
    }
    
    private function createConnection()
    {
        Utils::log('Authenticate'); 
        
        $secret = array(
            'username' => Utils::getEnvVar(Enum::ENV_USERNAME), 
            'apiKey'   => Utils::getEnvVar(Enum::ENV_API_KEY)
        );

        $identityEndpoint = Utils::getIdentityEndpoint();
        
        // Do connection stuff
        $connection = new Rackspace($identityEndpoint, $secret);
        $connection->appendUserAgent(Enum::USER_AGENT);

        // See if we can retrieve credentials
        $credentialsCacheFile = __DIR__ . '/Resource/' . Enum::CREDS_FILENAME;
        
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
            
            Utils::logf('   Saving credentials to %s', $credentialsCacheFile);
 
            // Save credentials
            fwrite($fp, serialize($connection->exportCredentials()));
            
        } else { 
            
            Utils::logf('   Loading credentials from %s', $credentialsCacheFile);
            
            // Read from file
            $string = fread($fp, $size);
            $connection->importCredentials(unserialize($string)); 
        }
        
        fclose($fp);
        
        Utils::logf('   Using identity endpoint: %s', $identityEndpoint);
        Utils::logf('   Using region: %s', Utils::getRegion());
        
        return $connection;
    }
        
}

require __DIR__ . '/../../../lib/php-opencloud.php';
Runner::run();