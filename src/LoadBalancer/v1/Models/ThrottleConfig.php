<?php

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a ThrottleConfig resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class ThrottleConfig extends AbstractResource implements Creatable, Updateable, Deletable, Retrievable
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
    public function create(array $userOptions)
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
        return $this->populateFromResponse($response);
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
        return $this->populateFromResponse($response);
    }
}