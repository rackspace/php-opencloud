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

namespace OpenCloud\Tests;

use Guzzle\Http\Message\Response;
use OpenCloud\Rackspace;

abstract class OpenCloudTestCase extends \PHPUnit_Framework_TestCase
{
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection\ResourceIterator';
    const RESPONSE_CLASS = 'Guzzle\Http\Message\Response';

    const ANNOTATION_FILE = 'mockFile';
    const ANNOTATION_PATH = 'mockPath';

    public $client;

    protected $mockPath = './';
    protected $testDir = '_response/';
    protected $testExt = '.resp';

    protected $currentMockSubscriber;

    public function newClient()
    {
        return new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'foo',
            'apiKey'   => 'bar'
        ));
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setUp()
    {
        $this->client = $this->newClient();

        $this->client->addSubscriber(new MockSubscriber());
        $this->client->authenticate();

        $this->setupObjects();
        $this->handleMockSubscribers();
    }

    public function tearDown()
    {
        $this->client = null;
    }

    public function handleMockSubscribers()
    {
        $reflection = new \ReflectionMethod(get_class($this), $this->getName());

        if (false == ($mockFile = self::parseDocBlock($reflection->getDocComment()))) {
            return;
        }

        $mockPath = self::parseDocBlock($reflection->getDocComment(), self::ANNOTATION_PATH);
        $mockFilePath = $this->getTestFilePath($mockFile, $mockPath);

        if (file_exists($mockFilePath)) {
            $this->getClient()->getEventDispatcher()->removeListener('request.before_send', 'onRequestBeforeSend');
            $this->addMockSubscriber($mockFilePath, 0);
        }
    }

    protected static function parseDocBlock($string, $annotation = self::ANNOTATION_FILE)
    {
        $pattern = sprintf('#\@%s\s(\w+)#', $annotation);
        preg_match($pattern, $string, $matches);

        return (isset($matches[1])) ? $matches[1] : false;
    }

    protected function getTestFilePath($file, $mockPath = null)
    {
        $mockPath = $mockPath ? : $this->mockPath;

        return ROOT_TEST_DIR . $mockPath . '/' . $this->testDir . $file . $this->testExt;
    }

    protected function addMockSubscriber($response)
    {
        $this->currentMockSubscriber = new MockSubscriber(array($response), true);
        $this->getClient()->addSubscriber($this->currentMockSubscriber);
    }

    public function setupObjects()
    {
    }

    public function makeResponse($body = null, $status = 200)
    {
        return new Response($status, array('Content-Type' => 'application/json'), $body);
    }

    public function isResponse($object)
    {
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $object);
    }

    public function isCollection($object)
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $object);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
