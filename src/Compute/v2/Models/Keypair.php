<?php declare(strict_types=1);

namespace Rackspace\Compute\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;

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
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postOsKeypairs(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteOskeypairs());
    }
}
