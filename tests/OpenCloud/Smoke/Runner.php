<?php

namespace OpenCloud\Smoke;

use Guzzle\Log\PsrLogAdapter;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Log\MessageFormatter;
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
        'Identity',
        'LoadBalancer',
        'ObjectStore',
        'Orchestration',
        'Queues',
        'Volume'
    );

    /**
     * @var bool If set to TRUE, all HTTP traffic over the wire will be outputted.
     */
    private $debugMode = false;
    
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
                    $this->debugMode = true;
                    break;
                case 'H':
                case 'help':
                    Utils::help();
                    exit();
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
                    exit();
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
        $client = $this->createClient();
        
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
            
            $class::factory($client, $this->included);
        }
    }
    
    private function createClient()
    {
        Utils::log('Authenticate'); 
        
        $secret = array(
            'username' => Utils::getEnvVar(Enum::ENV_USERNAME), 
            'apiKey'   => Utils::getEnvVar(Enum::ENV_API_KEY)
        );

        $identityEndpoint = Utils::getIdentityEndpoint();
        
        // Do connection stuff
        $client = new Rackspace($identityEndpoint, $secret);
        $client->setUserAgent($client->getUserAgent() . '/' . Enum::USER_AGENT);

        // enable logging
        if ($this->debugMode) {
            $client->addSubscriber(LogPlugin::getDebugPlugin());
        }

        $client->authenticate();

        Utils::logf('   Using identity endpoint: %s', $identityEndpoint);
        Utils::logf('   Using region: %s', Utils::getRegion());
        Utils::logf('   Token generated: %s', (string) $client->getToken());
        
        return $client;
    }
        
}

require __DIR__ . '/../../bootstrap.php';
Runner::run();