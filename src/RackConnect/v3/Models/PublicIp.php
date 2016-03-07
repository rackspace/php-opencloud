<?php

namespace Rackspace\RackConnect\v3\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a PublicIp resource in the RackConnect v3 service
 *
 * @property \Rackspace\RackConnect\v3\Api $api
 */
class PublicIp extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $created;

    /**
     * @var object
     */
    public $cloudServer;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $publicIpV;

    /**
     * @var string
     */
    public $status;

    /**
     * @var NULL
     */
    public $statusDetail;

    /**
     * @var string
     */
    public $updated;

    protected $aliases = [
        'cloud_server'  => 'cloudServer',
        'public_ip_v4'  => 'publicIpV',
        'status_detail' => 'statusDetail',
    ];

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postPublicIp(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deletePublicIp());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getPublicIp());
        return $this->populateFromResponse($response);
    }
}