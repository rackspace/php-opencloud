<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a Datastore resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Datastore extends AbstractResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $defaultVersion;

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

    /**
     * @var array
     */
    public $versions;

    protected $aliases = [
        'default_version' => 'defaultVersion',
    ];

    protected $resourceKey = 'datastore';

    protected $resourcesKey = 'datastores';

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getDatastore());
        $this->populateFromResponse($response);
    }
}