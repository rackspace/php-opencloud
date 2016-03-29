<?php declare(strict_types=1);

namespace Rackspace\Monitoring\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a Agent resource in the Monitoring v1 service
 *
 * @property \Rackspace\Monitoring\v1\Api $api
 */
class Agent extends AbstractResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var integer
     */
    public $lastConnected;

    protected $aliases = [
        'last_connected' => 'lastConnected',
    ];

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getAgent());
        $this->populateFromResponse($response);
    }
}