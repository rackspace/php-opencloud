<?php

namespace OpenCloud\Tests;

use PHPUnit_Framework_TestCase;
use OpenCloud\Rackspace;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Log\LogPlugin;

abstract class OpenCloudTestCase extends PHPUnit_Framework_TestCase
{
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection\ResourceIterator';
    const RESPONSE_CLASS   = 'Guzzle\Http\Message\Response';

    const ANNOTATION_FILE = 'mockFile';
    const ANNOTATION_PATH = 'mockPath';

    public $client;

    protected $mockPath = './';
    protected $testDir = '_response/';
    protected $testExt = '.resp';

    protected $currentMockSubscriber;

    public function newClient()
    {
        $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'foo',
            'apiKey'   => 'bar'
        ));

        $client->addSubscriber(new MockSubscriber());
        //$client->addSubscriber(LogPlugin::getDebugPlugin());

        $client->authenticate();

        return $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setUp()
    {
        $this->client = $this->newClient();
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
        $mockPath = $mockPath ?: $this->mockPath;

        return ROOT_TEST_DIR . $mockPath . '/' . $this->testDir . $file . $this->testExt;
    }

    protected function addMockSubscriber($response)
    {
        $this->currentMockSubscriber = new MockSubscriber(array($response), true);
        $this->getClient()->addSubscriber($this->currentMockSubscriber);
    }

    public function setupObjects() {}

    public function makeResponse($body = null, $status = 200)
    {
        return new Response($status, array('Content-Type' => 'application/json'), $body);
    }
    
}