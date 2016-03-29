<?php declare(strict_types=1);

namespace Rackspace\CDN\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

/**
 * Represents a Service resource in the CDN v1 service
 *
 * @property \Rackspace\CDN\v1\Api $api
 */
class Service extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $projectId;

    /**
     * @var array
     */
    public $domains;

    /**
     * @var array
     */
    public $origins;

    /**
     * @var array
     */
    public $restrictions;

    /**
     * @var array
     */
    public $caching;

    /**
     * @var string
     */
    public $flavorId;

    /**
     * @var object
     */
    public $logDelivery;

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    public $errors;

    /**
     * @var array
     */
    public $links;

    protected $aliases = array(
        'project_id' => 'projectId',
        'flavor_id' => 'flavorId',
        'log_delivery' => 'logDelivery',
    );

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postService(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putService());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteService());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getService());
        $this->populateFromResponse($response);
    }
}