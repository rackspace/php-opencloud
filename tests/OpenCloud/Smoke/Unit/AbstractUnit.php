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
use OpenCloud\Common\Service;

/**
 * Description of AbstractUnit
 * 
 * @link 
 */
abstract class AbstractUnit
{
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
        $service = $unit->setupService();
        $this->setService($service);
        
        // Run execution...
        $unit->main();
        $unit->teardown();
        
        return true;
    }
    
    public function setConnection(OpenStack $connection)
    {
        $this->connection = $connection;
        return $this;
    }
    
    public function getCredentials()
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
            'username' => Utils::getEnvVar('username'), 
            'apiKey'   => Utils::getEnvVar('apiKey')
        );
        $region = Utils::getEnvVar('region');

        // Do connection stuff
        $connection = new Rackspace($region, $secret);
        $connection->appendUserAgent(Enum::USER_AGENT);
        $this->setConnection($connection);
        
        // See if we can retrieve credentials
        $this->handleCredentials();
        
        Utils::logf('Using region: %s', $region);
    }
    
    public function handleCredentials()
    {
        $credentialsCacheFile = __DIR__ . '/../Resource/' . Enum::CREDS_FILENAME;
        
        // Does the credentials file already exist?
        if (!$fp = fopen($credentialsCacheFile, 'r')) {
            
            // If not, can we create a new one?            
            if (!is_writable($credentialsCacheFile)
                || false === ($fp = fopen($credentialsCacheFile, 'w'))
            ) {
                throw new Exception(sprintf(
                    'Credentials file [%s] needs to be writable',
                    $credentialsCacheFile
                ));
            }
            
            Utis::logf('Saving credentials to %s', $credentialsCacheFile);
 
            // Save credentials
            fwrite($fp, serialize($this->getConnection()->exportCredentials()));
            
        } else { 
            
            Utils::logf('Loading credentials from %s', CACHEFILE);
            
            // Read from file
            $string = fread($fp, filesize($credentialsCacheFile));
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
    
    public function shoulDelete($string)
    {
        return strpos($string, Enum::GLOBAL_PREFIX);
    }
    
}