<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\HasMetadata;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents a Server resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Server extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable, HasMetadata
{
    /**
     * @var string
     */
    public $oSDCFdiskConfig;

    /**
     * @var integer
     */
    public $oSEXTSTSpowerState;

    /**
     * @var string
     */
    public $oSEXTSTStaskState;

    /**
     * @var string
     */
    public $oSEXTSTSvmState;

    /**
     * @var string
     */
    public $accessIPv4;

    /**
     * @var string
     */
    public $accessIPv6;

    /**
     * @var object
     */
    public $addresses;

    /**
     * @var string
     */
    public $created;

    /**
     * @var object
     */
    public $flavor;

    /**
     * @var string
     */
    public $hostId;

    /**
     * @var string
     */
    public $id;

    /**
     * @var object
     */
    public $image;

    /**
     * @var array
     */
    public $links;

    /**
     * @var object
     */
    public $metadata;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $progress;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var string
     */
    public $userId;

    protected $aliases = [
        'OS-DCF:diskConfig'      => 'oSDCFdiskConfig',
        'OS-EXT-STS:power_state' => 'oSEXTSTSpowerState',
        'OS-EXT-STS:task_state'  => 'oSEXTSTStaskState',
        'OS-EXT-STS:vm_state'    => 'oSEXTSTSvmState',
        'tenant_id'              => 'tenantId',
        'user_id'                => 'userId',
    ];

    protected $resourceKey = 'server';

    protected $resourcesKey = 'servers';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postServer(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putServer());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteServer());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getServer());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function mergeMetadata(array $metadata)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function resetMetadata(array $metadata)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function parseMetadata(ResponseInterface $response)
    {
    }
}
