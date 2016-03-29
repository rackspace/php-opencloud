<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

/**
 * Represents a LoadBalancer resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class LoadBalancer extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $protocol;

    /**
     * @var integer
     */
    public $port;

    /**
     * @var string
     */
    public $algorithm;

    /**
     * @var string
     */
    public $status;

    /**
     * @var integer
     */
    public $timeout;

    /**
     * @var object
     */
    public $connectionLogging;

    /**
     * @var array
     */
    public $virtualIps;

    /**
     * @var array
     */
    public $nodes;

    /**
     * @var object
     */
    public $sessionPersistence;

    /**
     * @var object
     */
    public $connectionThrottle;

    /**
     * @var object
     */
    public $cluster;

    /**
     * @var object
     */
    public $created;

    /**
     * @var object
     */
    public $updated;

    /**
     * @var object
     */
    public $sourceAddresses;

    protected $resourceKey = 'loadBalancer';

    protected $resourcesKey = 'loadBalancers';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postLoadBalancer(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putLoadBalancer());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteLoadBalancer());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getLoadBalancer());
        $this->populateFromResponse($response);
    }
}