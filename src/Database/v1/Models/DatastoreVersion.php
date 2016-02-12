<?php

namespace Rackspace\Database\v1\Models;

/**
 * Represents a DatastoreVersion resource in the Database v1 service
 *
 * @property Rackspace\Database\v1\Api $api
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
        return $this->populateFromResponse($response);
    }


}
