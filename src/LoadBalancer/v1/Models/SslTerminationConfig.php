<?php

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a SslTerminationConfig resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class SslTerminationConfig extends AbstractResource implements Updateable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $certificate;

    /**
     * @var boolean
     */
    public $enabled;

    /**
     * @var boolean
     */
    public $secureTrafficOnly;

    /**
     * @var string
     */
    public $intermediateCertificate;

    /**
     * @var integer
     */
    public $securePort;

    protected $resourceKey = 'sslTermination';

    protected $resourcesKey = 'sslTerminations';

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putSslTerminationConfig());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteSslTerminationConfig());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getSslTerminationConfig());
        return $this->populateFromResponse($response);
    }
}