<?php declare(strict_types=1);

namespace Rackspace\Queue\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a Message resource in the Queue v1 service
 *
 * @property \Rackspace\Queue\v1\Api $api
 */
class Message extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $href;

    /**
     * @var integer
     */
    public $ttl;

    /**
     * @var integer
     */
    public $age;

    /**
     * @var object
     */
    public $body;

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postMessage(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteMessage());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getMessage());
        $this->populateFromResponse($response);
    }
}