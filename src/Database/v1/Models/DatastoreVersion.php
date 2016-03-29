<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a DatastoreVersion resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class DatastoreVersion extends AbstractResource implements Listable, Retrievable
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
