<?php declare(strict_types=1);

namespace Rackspace\Monitoring\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Check resource in the Monitoring v1 service
 *
 * @property \Rackspace\Monitoring\v1\Api $api
 */
class Check extends OperatorResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $type;

    /**
     * @var object
     */
    public $details;

    /**
     * @var array
     */
    public $monitoringZonesPoll;

    /**
     * @var integer
     */
    public $timeout;

    /**
     * @var integer
     */
    public $period;

    /**
     * @var string
     */
    public $targetAlias;

    protected $aliases = [
        'monitoring_zones_poll' => 'monitoringZonesPoll',
        'target_alias'          => 'targetAlias',
    ];

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postCheck(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putCheck());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteCheck());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getCheck());
        $this->populateFromResponse($response);
    }
}
