<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Flavor resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Flavor extends OperatorResource implements Listable, Retrievable
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $ram;

    protected $resourceKey = 'flavor';

    protected $resourcesKey = 'flavors';

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getFlavor());
        $this->populateFromResponse($response);
    }
}
