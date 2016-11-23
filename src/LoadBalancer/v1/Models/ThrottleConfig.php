<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a ThrottleConfig resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class ThrottleConfig extends OperatorResource implements Creatable, Updateable, Deletable, Retrievable
{
    /**
     * @var integer
     */
    public $maxConnections;

    /**
     * @var integer
     */
    public $minConnections;

    /**
     * @var integer
     */
    public $maxConnectionRate;

    /**
     * @var integer
     */
    public $rateInterval;

    protected $resourceKey = 'connectionThrottle';
    protected $resourcesKey = 'connectionThrottles';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postThrottleConfig(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putThrottleConfig());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteThrottleConfig());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getThrottleConfig());
        $this->populateFromResponse($response);
    }
}
