<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;

/**
 * Represents a Keypair resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Keypair extends AbstractResource implements Creatable, Listable, Deletable
{
    /**
     * @var string
     */
    public $fingerprint;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $publicKey;

    protected $aliases = [
        'public_key' => 'publicKey',
    ];

    protected $resourceKey = 'keypair';

    protected $resourcesKey = 'keypairs';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postKeypair(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteKeypair());
    }
}
