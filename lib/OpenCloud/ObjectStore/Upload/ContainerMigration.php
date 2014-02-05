<?php

namespace OpenCloud\ObjectStore\Upload;

use Guzzle\Batch\BatchBuilder;
use Guzzle\Common\Collection;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Url;
use OpenCloud\ObjectStore\Resource\Container;

class ContainerMigration
{
    protected $readQueue;

    protected $writeQueue;

    protected $oldContainer;

    protected $newContainer;

    protected $options = array();

    protected $defaults = array(
        'read.batchLimit'  => 1000,
        'read.pageLimit'   => 10000,
        'write.batchLimit' => 100
    );

    public static function factory(Container $old, Container $new, array $options = array())
    {
        $migration = new self();

        $migration->setOldContainer($old);
        $migration->setNewContainer($new);
        $migration->setOptions($options);

        $migration->setupReadQueue();
        $migration->setupWriteQueue();

        return $migration;
    }

    public function setOldContainer(Container $old)
    {
        $this->oldContainer = $old;
    }

    public function setNewContainer(Container $new)
    {
        $this->newContainer = $new;
    }

    public function setOptions(array $options)
    {
        $this->options = Collection::fromConfig($options, $this->defaults);
    }

    public function setupReadQueue()
    {
        $this->readQueue = BatchBuilder::factory()
            ->transferRequests($this->options->get('read.batchLimit'))
            ->build();
    }

    public function setupWriteQueue()
    {
        $this->writeQueue = BatchBuilder::factory()
            ->transferRequests($this->options->get('read.batchLimit'))
            ->build();
    }

    private function getClient()
    {
        return $this->newContainer->getService()->getClient();
    }

    protected function enqueueGetRequests()
    {
        $files = $this->oldContainer->objectList(array(
            'limit.total' => false,
            'limit.page'  => $this->options->get('read.pageLimit')
        ));

        foreach ($files as $file) {
            $this->readQueue->add(
                $this->getClient()->get($file->getUrl())
            );
        }
    }

    protected function sendGetRequests()
    {
        $this->enqueueGetRequests();
        return $this->readQueue->flush();
    }

    protected function createPutRequest(Response $response)
    {
        $segments = Url::factory($response->getEffectiveUrl())->getPathSegments();
        $name = end($segments);

        // Retrieve content and metadata
        $file = $this->newContainer->dataObject()->setName($name);
        $file->setMetadata($response->getHeaders(), true);

        return $this->getClient()->put(
            $file->getUrl(),
            $file::stockHeaders($file->getMetadata()->toArray()),
            $response->getBody()
        );
    }

    public function transfer()
    {
        $requests = $this->sendGetRequests();
        $this->readQueue = null;

        foreach ($requests as $key => $request) {
            $this->writeQueue->add($this->createPutRequest($request->getResponse()));
            unset($requests[$key]);
        }

        return $this->writeQueue->flush();
    }
}