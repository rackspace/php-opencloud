<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a DatastoreVersion resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class DatastoreVersion extends OperatorResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $datastore;

    /**
     * @var string
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

    protected $resourceKey = 'version';

    protected $resourcesKey = 'versions';

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getDatastoreVersion());
        $this->populateFromResponse($response);
    }
}
