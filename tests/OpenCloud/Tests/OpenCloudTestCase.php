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

abstract class OpenCloudTestCase extends PHPUnit_Framework_TestCase
{
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection';

    public static $client;

    protected $mockPath = './';
    protected $testDir = '_response/';
    protected $testExt = '.resp';

    protected $currentMockSubscriber;

    private static function newClient()
    {
        $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'foo',
            'apiKey'   => 'bar'
        ));

        $client->addSubscriber(new MockSubscriber());

        $client->authenticate();

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
        $reflection = new \ReflectionMethod(get_class($this), $this->getName());

        if (false == ($mockFile = self::parseDocBlock($reflection->getDocComment()))) {
            return;
        }

        $mockFilePath = $this->getTestFilePath($mockFile);

        if (file_exists($mockFilePath)) {
            $this->addMockSubscriber($mockFilePath);
        }
    }

    protected static function parseDocBlock($string)
    {
        preg_match('#\@mockFile\s(\w+)#', $string, $matches);
        return (isset($matches[1])) ? $matches[1] : false;
    }

    protected function getTestFilePath($file)
    {
        return ROOT_TEST_DIR . $this->mockPath . '/' . $this->testDir . $file . $this->testExt;
    }

    public function tearDown()
    {
        $this->unsetCurrentMockSubscriber();
    }

    protected function addMockSubscriber($response)
    {
        $this->currentMockSubscriber = new MockSubscriber(array($response));
        $this->getClient()->addSubscriber($this->currentMockSubscriber);
    }

    public function unsetCurrentMockSubscriber()
    {
        if ($this->currentMockSubscriber) {
            $this->getClient()->getEventDispatcher()->removeSubscriber($this->currentMockSubscriber);
            $this->currentMockSubscriber = null;
        }
    }

    public function setupClassDependencies()
    {
    }
    
}