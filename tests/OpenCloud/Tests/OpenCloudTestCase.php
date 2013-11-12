<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests;

use PHPUnit_Framework_TestCase;
use OpenCloud\Rackspace;
use Guzzle\Plugin\Mock\MockPlugin;

abstract class OpenCloudTestCase extends PHPUnit_Framework_TestCase
{
    
    public static $client;

    protected $testDir = '_response';
    protected $testExt = '.resp';

    private static function newClient()
    {
        $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'foo',
            'apiKey'   => 'bar'
        ));
        
        $client->addSubscriber(new MockSubscriber());
        
        return $client;
    }
    
    public function getClient()
    {
        if (!self::$client) {
            self::$client = self::newClient();
        }

        return self::$client;
    }

    public function setUp()
    {
        if (strpos($this->getName(), 'Response') === false) {
            return;
        }

        $testFile = preg_replace('#[test|\_]*#', '', $this->getName());
        $testPath = $this->getTestFilePath($testFile);

        if (file_exists($testPath)) {
            $this->addMockSubscriber($testPath);
        }
    }

    protected function getTestFilePath($file, $dir = false, $root = false)
    {
        if ($dir) {
            $dir = ($root) ? ROOT_TEST_DIR . DIRECTORY_SEPARATOR . $dir : $dir;
        } else {
            $dir = __DIR__;
        }
        return $dir . DIRECTORY_SEPARATOR . $this->testDir . DIRECTORY_SEPARATOR . $file . $this->testExt;
    }

    protected function addMockSubscriber($response)
    {
        $plugin = new MockPlugin(array($response), true);
        $this->getClient()->addSubscriber($plugin);
    }
    
}