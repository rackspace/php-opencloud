<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Node resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class Node extends OperatorResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $address;

    /**
     * @var integer
     */
    public $port;

    /**
     * @var string
     */
    public $condition;

    /**
     * @var string
     */
    public $status;

    /**
     * @var integer
     */
    public $weight;

    /**
     * @var string
     */
    public $type;

    protected $resourceKey = 'node';

    protected $resourcesKey = 'nodes';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postNode(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putNode());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteNode());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getNode());
        $this->populateFromResponse($response);
    }
}
