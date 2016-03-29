<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a ErrorPage resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class ErrorPage extends AbstractResource implements Creatable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $content;

    protected $resourceKey = 'errorpage';

    protected $resourcesKey = 'errorpages';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postErrorPage(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteErrorPage());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getErrorPage());
        $this->populateFromResponse($response);
    }
}